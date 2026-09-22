<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Admission;
use App\Models\PatientTransfer;
use App\Models\DailyCensus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();
        
        // Ringkasan Statistik Utama
        $totalRooms = Room::where('is_active', true)->count();
        $totalBeds = Room::where('is_active', true)->sum('capacity');
        $activePatients = Admission::where('status', 'active')->count();
        $pendingTransfers = PatientTransfer::where('status', 'pending')->count();
        
        $overallBor = ($totalBeds > 0) ? round(($activePatients / $totalBeds) * 100, 1) : 0;

        // Kelompok Ruangan Per Kategori
        $categoryFilter = $request->get('category');
        $query = Room::where('is_active', true);
        if ($categoryFilter) {
            $query->where('category', $categoryFilter);
        }
        $rooms = $query->withCount(['admissions as current_patients' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        $categories = Room::select('category')->distinct()->pluck('category');

        return view('dashboard', compact(
            'totalRooms',
            'totalBeds',
            'activePatients',
            'pendingTransfers',
            'overallBor',
            'rooms',
            'categories',
            'categoryFilter'
        ));
    }
}
