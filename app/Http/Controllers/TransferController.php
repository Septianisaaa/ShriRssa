<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\PatientTransfer;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function index()
    {
        $pendingTransfers = PatientTransfer::with(['admission.patient', 'fromRoom', 'toRoom'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $historyTransfers = PatientTransfer::with(['admission.patient', 'fromRoom', 'toRoom'])
            ->where('status', '!=', 'pending')
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        $rooms = Room::where('is_active', true)->get();

        return view('transfers.index', compact('pendingTransfers', 'historyTransfers', 'rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'admission_id' => 'required|exists:admissions,id',
            'to_room_id' => 'required|exists:rooms,id|different:from_room_id',
            'transfer_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $admission = Admission::findOrFail($validated['admission_id']);

        PatientTransfer::create([
            'admission_id' => $admission->id,
            'from_room_id' => $admission->current_room_id,
            'to_room_id' => $validated['to_room_id'],
            'transfer_date' => $validated['transfer_date'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Permohonan Mutasi Transfer Out telah dikirim ke Ruangan Tujuan!');
    }

    public function accept(PatientTransfer $transfer)
    {
        DB::transaction(function () use ($transfer) {
            $transfer->status = 'accepted';
            $transfer->accepted_at = Carbon::now();
            $transfer->save();

            // Update lokasi ruangan aktif pasien ke ruangan baru
            $admission = $transfer->admission;
            $fromRoom = $transfer->fromRoom;
            $toRoom = $transfer->toRoom;

            $admission->current_room_id = $transfer->to_room_id;
            $admission->save();

            // Sync sensus harian secara real-time untuk ruangan asal dan ruangan tujuan
            $transferDateStr = Carbon::parse($transfer->transfer_date)->toDateString();
            if ($fromRoom) {
                \App\Services\CensusCalculatorService::syncRoomDate($fromRoom, $transferDateStr);
            }
            if ($toRoom) {
                \App\Services\CensusCalculatorService::syncRoomDate($toRoom, $transferDateStr);
            }
        });

        return redirect()->back()->with('success', 'Mutasi Pasien (Transfer In) berhasil dikonfirmasi! Data pasien otomatis berpindah ke ruangan Anda.');
    }

    public function reject(PatientTransfer $transfer)
    {
        $transfer->status = 'rejected';
        $transfer->save();

        return redirect()->back()->with('info', 'Permohonan Mutasi Pasien ditolak.');
    }
}
