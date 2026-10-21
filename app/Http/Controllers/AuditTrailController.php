<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditTrailController extends Controller
{
    /**
     * Menampilkan daftar riwayat aktivitas sistem (Audit Trail).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Audit Trail hanya dapat diakses oleh Petugas SHRI (Superadmin)
        abort_unless(
            $user && $user->isSuperAdmin(),
            403,
            'Anda tidak memiliki izin untuk mengakses halaman Audit Trail.'
        );

        $query = AuditTrail::query()
            ->with('user')
            ->latest('created_at');

        // 1. Filter Pencarian Teks
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%");
            });
        }

        // 2. Filter Tanggal Mulai
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // 3. Filter Tanggal Selesai
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // 4. Filter Jenis Aksi
        if ($request->filled('action')) {
            $query->where('action', strtolower($request->action));
        }

        // 5. Filter Modul
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        $activities = $query
            ->paginate(15)
            ->withQueryString();

        // Ringkasan Statistik Audit Trail
        $today = Carbon::today();
        $totalActivities = AuditTrail::count();
        $todayActivities = AuditTrail::whereDate('created_at', $today)->count();

        $activeUsersToday = AuditTrail::whereDate('created_at', $today)
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        // Pilihan filter Aksi
        $standardActions = ['create', 'update', 'delete', 'login', 'logout'];
        $dbActions = AuditTrail::whereNotNull('action')
            ->where('action', '!=', '')
            ->distinct()
            ->pluck('action')
            ->toArray();
        $actions = array_values(array_unique(array_merge($standardActions, $dbActions)));

        // Pilihan filter Modul
        $standardModules = [
            'Sensus Harian',
            'Mutasi Pasien',
            'Kelola Ruangan',
            'Kelola Akun',
            'Profil Pengguna',
            'Autentikasi'
        ];
        $dbModules = AuditTrail::whereNotNull('module')
            ->where('module', '!=', '')
            ->distinct()
            ->pluck('module')
            ->toArray();
        $modules = array_values(array_unique(array_merge($standardModules, $dbModules)));

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