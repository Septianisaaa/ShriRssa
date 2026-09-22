<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function index()
    {
        $adminRuangUsers = User::where('role', 'admin')->with('room')->orderBy('created_at', 'desc')->get();
        $superAdminUsers = User::where('role', 'superadmin')->orderBy('created_at', 'desc')->get();
        $rooms = Room::where('is_active', true)->get();

        return view('users.index', compact('adminRuangUsers', 'superAdminUsers', 'rooms'));
    }

    public function storeAdminRuang(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username|alpha_dash',
            'email' => 'required|string|email|max:255|unique:users,email',
            'room_id' => 'required|exists:rooms,id',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => strtolower($validated['email']),
            'room_id' => $validated['room_id'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        return redirect()->back()->with('success', 'Akun Admin Ruangan baru berhasil didaftarkan!');
    }

    public function destroy(User $user)
    {
        if ($user->isSuperAdmin() && User::where('role', 'superadmin')->count() <= 1) {
            return redirect()->back()->with('info', 'Tidak dapat menghapus satu-satunya akun Petugas SHRI (Superadmin).');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->back()->with('success', "Akun {$userName} berhasil dihapus.");
    }
}
