<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            if ($request->is('admin-ruang*')) {
                return redirect()->route('admin_ruang.login')->with('info', 'Silakan login terlebih dahulu sebagai Admin Ruangan.');
            }
            return redirect()->route('shri.login')->with('info', 'Silakan login terlebih dahulu sebagai Petugas SHRI.');
        }

        $user = Auth::user();
        if (!in_array($user->role, $roles)) {
            $redirectRoute = $user->isSuperAdmin() ? 'shri.dashboard' : 'admin_ruang.dashboard';
            return redirect()->route($redirectRoute)->with('info', 'Anda tidak memiliki hak akses untuk halaman tersebut.');
        }

        return $next($request);
    }
}
