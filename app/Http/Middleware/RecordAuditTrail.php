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
    public function handle(Request $request, Closure $next): Response
    {
        // Jalankan permintaan terlebih dahulu.
        $response = $next($request);

        $user = Auth::user();
        $method = strtoupper($request->method());

        // Catat hanya permintaan yang mengubah data.
        $allowedMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];

        if (
            !$user ||
            !in_array($method, $allowedMethods, true) ||
            $response->getStatusCode() >= 400
        ) {
            return $response;
        }

        // Abaikan autentikasi dan halaman audit trail.
        $path = $request->path();

        if (
            $path === 'login' ||
            $path === 'logout' ||
            Str::is('*audit-trail*', $path)
        ) {
            return $response;
        }

        // Tentukan tindakan berdasarkan HTTP method.
        $action = match ($method) {
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
        };

        // Ambil nama route atau URL sebagai sumber modul.
        $routeName = $request->route()?->getName();
        $moduleSource = $routeName ?: $path;

        $module = Str::of($moduleSource)
            ->replace(['admin_ruang.', 'shri.'], '')
            ->before('.')
            ->replace(['-', '_'], ' ')
            ->title()
            ->toString();

        if ($module === '') {
            $module = 'Lainnya';
        }

        // Simpan catatan audit tanpa isi form atau data pasien.
        try {
            AuditTrail::create([
                'user_id' => $user->id,
                'username' => $user->username
                    ?? $user->email
                    ?? null,
                'user_name' => $user->name
                    ?? $user->nama_lengkap
                    ?? null,
                'action' => $action,
                'module' => $module,
                'description' => "Aktivitas {$action} berhasil.",
                'old_values' => null,
                'new_values' => null,
                'method' => $method,
                'url' => $path,
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit(
                    (string) $request->userAgent(),
                    1000
                ),
            ]);
        } catch (Throwable $e) {
            // Catat kegagalan agar dapat diperiksa di log Laravel.
            report($e);
        }

        return $response;
    }
}