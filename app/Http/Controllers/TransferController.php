<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\PatientTransfer;
use App\Models\Room;
use App\Services\CensusCalculatorService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class TransferController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pendingQuery = PatientTransfer::with([
            'admission.patient',
            'fromRoom',
            'toRoom',
        ])->where('status', 'pending');

        $historyQuery = PatientTransfer::with([
            'admission.patient',
            'fromRoom',
            'toRoom',
        ])->where('status', '!=', 'pending');

        if ($user && $user->isAdminRuang() && $user->room_id) {
            $pendingQuery->where(function ($query) use ($user) {
                $query->where('from_room_id', $user->room_id)
                    ->orWhere('to_room_id', $user->room_id);
            });

            $historyQuery->where(function ($query) use ($user) {
                $query->where('from_room_id', $user->room_id)
                    ->orWhere('to_room_id', $user->room_id);
            });
        }

        $pendingTransfers = $pendingQuery
            ->orderByDesc('created_at')
            ->get();

        $historyTransfers = $historyQuery
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        $rooms = Room::where('is_active', true)->get();

        return view('transfers.index', compact(
            'pendingTransfers',
            'historyTransfers',
            'rooms'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'admission_id' => 'required|exists:admissions,id',
            'to_room_id' => 'required|exists:rooms,id',
            'transfer_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $admission = Admission::findOrFail(
            $validated['admission_id']
        );

        if (
            $user &&
            $user->isAdminRuang() &&
            $user->room_id &&
            $admission->current_room_id != $user->room_id
        ) {
            abort(
                403,
                'Anda hanya dapat mengajukan mutasi untuk pasien yang dirawat di ruangan Anda.'
            );
        }

        if ($admission->status !== 'active') {
            return back()->withErrors([
                'admission_id' => 'Pasien tidak sedang aktif dirawat.',
            ])->withInput();
        }

        if (
            $admission->current_room_id == $validated['to_room_id']
        ) {
            return back()->withErrors([
                'to_room_id' => 'Ruangan tujuan harus berbeda dengan ruangan saat ini.',
            ])->withInput();
        }

        PatientTransfer::create([
            'admission_id' => $admission->id,
            'from_room_id' => $admission->current_room_id,
            'to_room_id' => $validated['to_room_id'],
            'transfer_date' => $validated['transfer_date'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with(
            'success',
            'Permohonan mutasi transfer out berhasil dikirim ke ruangan tujuan.'
        );
    }

    public function accept(PatientTransfer $transfer)
    {
        $user = Auth::user();

        // Hanya ruangan tujuan yang boleh menerima mutasi.
        if (
            $user &&
            $user->isAdminRuang() &&
            $user->room_id &&
            $transfer->to_room_id != $user->room_id
        ) {
            abort(
                403,
                'Anda tidak berhak mengonfirmasi mutasi masuk ke ruangan lain.'
            );
        }

        DB::transaction(function () use ($transfer) {
            // Kunci transfer agar tidak diproses bersamaan.
            $lockedTransfer = PatientTransfer::whereKey($transfer->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedTransfer->status !== 'pending') {
                throw new RuntimeException(
                    'Permohonan mutasi sudah diproses sebelumnya.'
                );
            }

            $admission = Admission::whereKey(
                $lockedTransfer->admission_id
            )->lockForUpdate()->firstOrFail();

            // Pastikan pasien masih aktif di ruang asal.
            if (
                $admission->status !== 'active' ||
                $admission->current_room_id != $lockedTransfer->from_room_id
            ) {
                throw new RuntimeException(
                    'Data ruang aktif pasien sudah berubah. Periksa kembali sebelum menerima mutasi.'
                );
            }

            $lockedTransfer->status = 'accepted';
            $lockedTransfer->accepted_at = Carbon::now();
            $lockedTransfer->save();

            // Pindahkan pasien hanya setelah mutasi diterima.
            $admission->current_room_id = $lockedTransfer->to_room_id;
            $admission->admission_type = 'transfer';
            $admission->status = 'active';
            $admission->save();

            $transferDate = Carbon::parse(
                $lockedTransfer->transfer_date
            )->toDateString();

            $lockedTransfer->load(['fromRoom', 'toRoom']);

            if ($lockedTransfer->fromRoom) {
                CensusCalculatorService::syncRoomDate(
                    $lockedTransfer->fromRoom,
                    $transferDate
                );
            }

            if ($lockedTransfer->toRoom) {
                CensusCalculatorService::syncRoomDate(
                    $lockedTransfer->toRoom,
                    $transferDate
                );
            }
        });

        return back()->with(
            'success',
            'Mutasi berhasil diterima. Pasien telah dipindahkan ke ruangan tujuan.'
        );
    }

    public function reject(Request $request, PatientTransfer $transfer)
    {
        $user = Auth::user();

        // Hanya ruangan tujuan yang boleh menolak mutasi.
        if (
            $user &&
            $user->isAdminRuang() &&
            $user->room_id &&
            $transfer->to_room_id != $user->room_id
        ) {
            abort(
                403,
                'Anda tidak berhak menolak mutasi ini.'
            );
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($transfer, $validated) {
            $lockedTransfer = PatientTransfer::whereKey($transfer->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedTransfer->status !== 'pending') {
                throw new RuntimeException(
                    'Permohonan mutasi sudah diproses sebelumnya.'
                );
            }

            // Tolak mutasi dan simpan alasan.
            $lockedTransfer->status = 'rejected';
            $lockedTransfer->notes = $validated['rejection_reason'];
            $lockedTransfer->save();

            /*
             * PENTING:
             * Jangan mengubah admissions.current_room_id.
             * Jangan mengubah admissions.status.
             * Pasien tetap aktif di ruang asal.
             */
        });

        return back()->with(
            'success',
            'Mutasi ditolak. Pasien tetap aktif di ruangan asal.'
        );
    }
}