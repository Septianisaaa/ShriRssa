<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // Portal Utama Selection Page
    public function showPortal()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return redirect()->route($user->isSuperAdmin() ? 'shri.dashboard' : 'admin_ruang.dashboard');
        }

        return view('auth.portal');
    }

    // 1. Halaman Login Petugas SHRI
    public function showShriLogin()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->isSuperAdmin() ? 'shri.dashboard' : 'admin_ruang.dashboard');
        }

        return view('auth.shri-login');
    }

    // 2. Halaman Registrasi Petugas SHRI Baru
    public function showShriRegister()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->isSuperAdmin() ? 'shri.dashboard' : 'admin_ruang.dashboard');
        }

        return view('auth.shri-register');
    }

    // 3. Halaman Login Admin Ruangan (Tanpa Registrasi)
    public function showAdminRuangLogin()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->isSuperAdmin() ? 'shri.dashboard' : 'admin_ruang.dashboard');
        }

        return view('auth.admin-login');
    }

    // Process Login Petugas SHRI
    public function loginShri(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginType => $credentials['login'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->isSuperAdmin()) {
                Auth::logout();
                return redirect()->route('shri.login')
                    ->withErrors(['login' => 'Akun ini adalah Admin Ruangan. Silakan gunakan Halaman Login Admin Ruangan.']);
            }

            // Catat log audit trail login berhasil
            AuditTrail::record('login', 'Autentikasi', "Login berhasil sebagai Petugas SHRI (Admin)", $user, null, null, $request);

            $request->session()->regenerate();
            return redirect()->intended(route('shri.dashboard'))->with('success', "Selamat datang kembali, {$user->name}!");
        }

        return redirect()->route('shri.login')
            ->withErrors(['login' => 'Email/Username atau password yang Anda masukkan salah.'])
            ->withInput($request->only('login'));
    }

    // Process Registrasi Petugas SHRI Baru
    public function registerShri(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username|alpha_dash',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.letters' => 'Password harus mengandung huruf.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.symbols' => 'Password harus mengandung simbol (contoh: @, #, $, !, %, dll).',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => strtolower($validated['email']),
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'superadmin',
        ]);

        Auth::login($user);

        // Catat log audit trail registrasi akun baru
        AuditTrail::record('create', 'Autentikasi', "Registrasi akun Petugas SHRI baru: {$user->name} (@{$user->username})", $user, ['name' => $user->name, 'username' => $user->username, 'email' => $user->email], null, $request);

        return redirect()->route('shri.dashboard')->with('success', 'Registrasi Petugas SHRI berhasil! Anda telah masuk sebagai Admin.');
    }

    // Process Login Admin Ruangan
    public function loginAdminRuang(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginType => $credentials['login'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->isAdminRuang()) {
                Auth::logout();
                return redirect()->route('admin_ruang.login')
                    ->withErrors(['login' => 'Akun ini adalah Petugas SHRI (Admin). Silakan gunakan Halaman Login Petugas SHRI.']);
            }

            // Catat log audit trail login berhasil
            $roomName = $user->room ? $user->room->name : 'Ruangan';
            AuditTrail::record('login', 'Autentikasi', "Login berhasil sebagai Admin Ruangan ({$roomName})", $user, null, null, $request);

            $request->session()->regenerate();
            return redirect()->intended(route('admin_ruang.dashboard'))->with('success', "Selamat datang, {$user->name}!");
        }

        return redirect()->route('admin_ruang.login')
            ->withErrors(['login' => 'Email/Username atau password yang Anda masukkan salah.'])
            ->withInput($request->only('login'));
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            AuditTrail::record('logout', 'Autentikasi', "Pengguna keluar (logout) dari sistem", $user, null, null, $request);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah keluar dari sistem.');
    }
}
