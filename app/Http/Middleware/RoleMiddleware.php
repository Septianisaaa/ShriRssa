<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            if ($request->is('admin-ruang*')) {
                return redirect()->route('admin_ruang.login');
            }

            return redirect()->route('shri.login');
        }

        if (
            $request->is('shri*') &&
            !$user->isSuperAdmin()
        ) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if (
            $request->is('admin-ruang*') &&
            ($user->isSuperAdmin() || !$user->room_id)
        ) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}