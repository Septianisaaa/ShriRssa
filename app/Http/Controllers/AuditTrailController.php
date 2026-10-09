<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        // Audit Trail hanya dapat diakses Super Admin.
        $user = Auth::user();

        abort_unless(
            $user && $user->isSuperAdmin(),
            403,
            'Anda tidak memiliki akses ke halaman Audit Trail.'
        );

        $query = AuditTrail::query()
            ->with('user')
            ->latest('created_at');

        // Pencarian aktivitas, pengguna, modul, dan alamat IP.
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Filter tanggal awal.
        if ($request->filled('date_from')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }

        // Filter tanggal akhir.
        if ($request->filled('date_to')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }

        // Filter jenis aktivitas.
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter modul.
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        $activities = $query
            ->paginate(10)
            ->withQueryString();

        // Ringkasan aktivitas hari ini.
        $today = Carbon::today();

        $totalActivities = AuditTrail::count();

        $todayActivities = AuditTrail::whereDate(
            'created_at',
            $today
        )->count();

        $activeUsersToday = AuditTrail::whereDate(
            'created_at',
            $today
        )
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        // Pilihan filter diambil dari data yang tersedia.
        $actions = AuditTrail::whereNotNull('action')
            ->where('action', '!=', '')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $modules = AuditTrail::whereNotNull('module')
            ->where('module', '!=', '')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        return view('audit-trail.index', compact(
            'activities',
            'totalActivities',
            'todayActivities',
            'activeUsersToday',
            'actions',
            'modules'
        ));
    }
}