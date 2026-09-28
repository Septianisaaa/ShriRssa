<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\PatientTransfer;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Base Query with Role Scoping
        $query = Room::where('is_active', true);
        if ($user && $user->isAdminRuang() && $user->room_id) {
            $query->where('id', $user->room_id);
        }

        // 2. Fetch distinct options for filters
        $allInstalasi = Room::where('is_active', true)->select('category')->distinct()->pluck('category')->filter()->values();
        $allClasses = Room::where('is_active', true)->select('room_class')->distinct()->pluck('room_class')->filter()->values();

        // 3. Apply Filters
        $filterInstalasi = $request->get('instalasi');
        $filterKelas = $request->get('kelas');
        $filterStatus = $request->get('status');
        $filterSearch = $request->get('search');

        if ($filterInstalasi) {
            $query->where('category', $filterInstalasi);
        }

        if ($filterKelas) {
            $query->where('room_class', $filterKelas);
        }

        if ($filterSearch) {
            $query->where(function ($q) use ($filterSearch) {
                $q->where('name', 'like', "%{$filterSearch}%")
                    ->orWhere('code', 'like', "%{$filterSearch}%");
            });
        }

        // 4. Fetch Rooms with active patient count
        $allRooms = $query->withCount(['admissions as current_patients' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        // Attach computed status and percentage to each room
        $allRooms->transform(function ($room) {
            $cap = (int) $room->capacity;
            $curr = (int) $room->current_patients;
            $pct = ($cap > 0) ? min(100, round(($curr / $cap) * 100)) : 0;

            if ($pct >= 100) {
                $statusLabel = 'Penuh';
                $statusBadgeClass = 'danger';
            } elseif ($pct >= 71) {
                $statusLabel = 'Hampir Penuh';
                $statusBadgeClass = 'warning';
            } else {
                $statusLabel = 'Tersedia';
                $statusBadgeClass = 'success';
            }

            $room->occupancy_percentage = $pct;
            $room->status_label = $statusLabel;
            $room->status_badge_class = $statusBadgeClass;

            return $room;
        });

        // Filter by Status if requested
        if ($filterStatus) {
            $allRooms = $allRooms->filter(fn ($r) => $r->status_label === $filterStatus)->values();
        }

        // 5. Paginate 15 data per page
        $perPage = 15;
        $currentPage = Paginator::resolveCurrentPage() ?: 1;
        $currentPageItems = $allRooms->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $rooms = new LengthAwarePaginator(
            $currentPageItems,
            $allRooms->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        // 6. Calculate Hospital/Scoped Aggregated Metrics
        if ($user && $user->isAdminRuang() && $user->room_id) {
            $scopedRoomIds = [$user->room_id];
        } else {
            $scopedRoomIds = Room::where('is_active', true)->pluck('id')->toArray();
        }

        $totalBeds = Room::whereIn('id', $scopedRoomIds)->sum('capacity');
        $activePatients = Admission::whereIn('current_room_id', $scopedRoomIds)->where('status', 'active')->count();
        $availableBeds = max(0, $totalBeds - $activePatients);

        $occupiedPct = ($totalBeds > 0) ? round(($activePatients / $totalBeds) * 100, 1) : 0;
        $availablePct = ($totalBeds > 0) ? round(($availableBeds / $totalBeds) * 100, 1) : 0;

        $pendingTransfersQuery = PatientTransfer::where('status', 'pending');
        if ($user && $user->isAdminRuang() && $user->room_id) {
            $pendingTransfersQuery->where(function ($q) use ($user) {
                $q->where('from_room_id', $user->room_id)
                    ->orWhere('to_room_id', $user->room_id);
            });
        }
        $pendingTransfersCount = $pendingTransfersQuery->count();

        return view('dashboard', compact(
            'rooms',
            'allInstalasi',
            'allClasses',
            'filterInstalasi',
            'filterKelas',
            'filterStatus',
            'filterSearch',
            'totalBeds',
            'activePatients',
            'availableBeds',
            'occupiedPct',
            'availablePct',
            'pendingTransfersCount'
        ));
    }
}
