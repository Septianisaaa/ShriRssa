<?php

namespace App\Http\Middleware;

use App\Models\AuditTrail;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RecordAuditTrail
{
    /**
     * Menangani pencatatan aktivitas audit trail secara otomatis untuk setiap aksi mutasi data.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jalankan request terlebih dahulu untuk memastikan operasi berhasil.
        $response = $next($request);

        $user = Auth::user();
        $method = strtoupper($request->method());

        // Catat hanya aksi yang mengubah status/data (POST, PUT, PATCH, DELETE)
        $allowedMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];

        if (
            !$user ||
            !in_array($method, $allowedMethods, true) ||
            $response->getStatusCode() >= 400
        ) {
            return $response;
        }

        $path = $request->path();

        // Abaikan halaman audit trail dan request internal tertentu
        if (
            Str::is('*audit-trail*', $path) ||
            Str::is('_debugbar*', $path)
        ) {
            return $response;
        }

        // Tentukan aksi default berdasarkan HTTP method
        $action = match ($method) {
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
        };

        // Identifikasi modul dan deskripsi berbasis route name atau path
        $routeName = $request->route()?->getName() ?? $path;
        [$module, $action, $description] = $this->resolveAuditDetails($routeName, $action, $request);

        // Bersihkan data input agar tidak menyimpan password dan token sensitif
        $safeNewValues = $this->sanitizeInputs($request->all());

        try {
            AuditTrail::create([
                'user_id' => $user->id,
                'username' => $user->username ?? $user->email ?? 'Sistem',
                'user_name' => $user->name ?? 'Pengguna',
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'old_values' => null,
                'new_values' => !empty($safeNewValues) ? $safeNewValues : null,
                'method' => $method,
                'url' => '/' . ltrim($path, '/'),
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 1000),
            ]);
        } catch (Throwable $e) {
            report($e);
        }

        return $response;
    }

    /**
     * Menentukan nama modul, jenis aksi, dan deskripsi aktivitas yang mudah dipahami.
     */
    protected function resolveAuditDetails(string $routeName, string $defaultAction, Request $request): array
    {
        $action = $defaultAction;
        $module = 'Sistem';
        $desc = "Aktivitas {$defaultAction} berhasil diproses.";

        if (str_contains($routeName, 'sensus.patient.store') || str_contains($routeName, 'census.patient.store')) {
            $module = 'Sensus Harian';
            $action = 'create';
            $patientName = $request->input('name', 'Pasien');
            $rmNumber = $request->input('rm_number', '-');
            $desc = "Pendaftaran pasien rawat inap baru: {$patientName} (No. RM: {$rmNumber})";
        } elseif (str_contains($routeName, 'sensus.patient.discharge') || str_contains($routeName, 'census.patient.discharge')) {
            $module = 'Sensus Harian';
            $action = 'update';
            $condition = $request->input('discharge_condition', 'Pulang / Selesai Rawat');
            $desc = "Pemulangan pasien (KRS) dengan kondisi: {$condition}";
        } elseif (str_contains($routeName, 'transfers.store')) {
            $module = 'Mutasi Pasien';
            $action = 'create';
            $desc = "Pengajuan mutasi / pindah ruangan pasien antar bangsal";
        } elseif (str_contains($routeName, 'transfers.accept')) {
            $module = 'Mutasi Pasien';
            $action = 'update';
            $desc = "Konfirmasi penerimaan (terima) mutasi pasien ke ruangan";
        } elseif (str_contains($routeName, 'transfers.reject')) {
            $module = 'Mutasi Pasien';
            $action = 'update';
            $reason = $request->input('rejection_reason', '');
            $desc = "Penolakan mutasi pasien" . ($reason ? ": {$reason}" : "");
        } elseif (str_contains($routeName, 'room_management.store_installation')) {
            $module = 'Kelola Ruangan';
            $action = 'create';
            $name = $request->input('name', '-');
            $desc = "Menambahkan instalasi baru: {$name}";
        } elseif (str_contains($routeName, 'room_management.store_room_class')) {
            $module = 'Kelola Ruangan';
            $action = 'create';
            $name = $request->input('name', '-');
            $desc = "Menambahkan kelas perawatan baru: {$name}";
        } elseif (str_contains($routeName, 'room_management.store')) {
            $module = 'Kelola Ruangan';
            $action = 'create';
            $name = $request->input('name', '-');
            $code = $request->input('code', '-');
            $desc = "Menambahkan ruangan rawat inap: {$name} ({$code})";
        } elseif (str_contains($routeName, 'room_management.update')) {
            $module = 'Kelola Ruangan';
            $action = 'update';
            $name = $request->input('name', '-');
            $desc = "Memperbarui informasi ruangan rawat inap: {$name}";
        } elseif (str_contains($routeName, 'room_management.destroy')) {
            $module = 'Kelola Ruangan';
            $action = 'delete';
            $desc = "Menghapus data ruangan rawat inap";
        } elseif (str_contains($routeName, 'users.store_admin_ruang')) {
            $module = 'Kelola Akun';
            $action = 'create';
            $name = $request->input('name', '-');
            $username = $request->input('username', '-');
            $desc = "Mendaftarkan akun Admin Ruangan baru: {$name} (@{$username})";
        } elseif (str_contains($routeName, 'users.destroy')) {
            $module = 'Kelola Akun';
            $action = 'delete';
            $desc = "Menghapus akun pengguna dari sistem";
        } elseif (str_contains($routeName, 'profile.update')) {
            $module = 'Profil Pengguna';
            $action = 'update';
            $desc = "Pembaruan profil pengguna";
        } elseif (str_contains($routeName, 'profile.password')) {
            $module = 'Profil Pengguna';
            $action = 'update';
            $desc = "Pembaruan kata sandi (password) akun";
        } else {
            // Pemetaan umum berdasarkan teks route
            $module = Str::of($routeName)
                ->replace(['admin_ruang.', 'shri.'], '')
                ->before('.')
                ->replace(['-', '_'], ' ')
                ->title()
                ->toString();

            if (empty($module)) {
                $module = 'Umum';
            }

            $desc = "Aktivitas {$action} pada modul {$module}";
        }

        return [$module, $action, $desc];
    }

    /**
     * Memfilter input yang berpotensi sensitif atau objek biner besar.
     */
    protected function sanitizeInputs(array $inputs): array
    {
        $filtered = [];
        $sensitiveKeys = [
            'password',
            'password_confirmation',
            'current_password',
            '_token',
            '_method',
        ];

        foreach ($inputs as $key => $value) {
            if (in_array(strtolower($key), $sensitiveKeys, true)) {
                continue;
            }

            if ($value instanceof \Illuminate\Http\UploadedFile) {
                $filtered[$key] = '[File: ' . $value->getClientOriginalName() . ']';
            } elseif (is_array($value)) {
                $filtered[$key] = $this->sanitizeInputs($value);
            } elseif (is_scalar($value) || is_null($value)) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }
}