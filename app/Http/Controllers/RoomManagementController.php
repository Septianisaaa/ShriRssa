<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Installation;
use App\Models\RoomClass;
use Illuminate\Http\Request;

class RoomManagementController extends Controller
{
    /**
     * Halaman Kelola Ruangan
     */
    public function index()
    {
        // Ambil data instalasi aktif
        $installations = Installation::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Ambil data kelas perawatan aktif
        $roomClasses = RoomClass::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Ambil semua ruangan
        $rooms = Room::orderBy('name')
            ->orderBy('room_class')
            ->get();

        return view('room-management.index', compact(
            'rooms',
            'installations',
            'roomClasses'
        ));
    }


    /**
     * Tambah Instalasi
     */
    public function storeInstallation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:installations,name',
        ]);

        Installation::create([
            'name' => $validated['name'],
            'is_active' => true,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Instalasi berhasil ditambahkan.');
    }


    /**
     * Tambah Kelas Perawatan
     */
    public function storeRoomClass(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:room_classes,name',
        ]);

        RoomClass::create([
            'name' => $validated['name'],
            'is_active' => true,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Kelas perawatan berhasil ditambahkan.');
    }


    /**
     * Tambah Ruangan
     */
        public function storeRoom(Request $request)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:100|unique:rooms,code',
                'installation' => 'required|string',
                'room_class' => 'required|string',
                'capacity' => 'required|integer|min:0',
            ]);

            Room::create([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'installation' => $validated['installation'],
                'room_class' => $validated['room_class'],
                'capacity' => $validated['capacity'],
                'is_active' => true,
            ]);

            return redirect()
                ->back()
                ->with('success', 'Ruangan berhasil ditambahkan.');
        }


    /**
     * Update Ruangan
     */
        public function updateRoom(Request $request, Room $room)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:100|unique:rooms,code,' . $room->id,
                'installation' => 'required|string',
                'room_class' => 'required|string',
                'capacity' => 'required|integer|min:0',
            ]);

            $room->update([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'installation' => $validated['installation'],
                'room_class' => $validated['room_class'],
                'capacity' => $validated['capacity'],
            ]);

            return redirect()
                ->back()
                ->with('success', 'Ruangan berhasil diperbarui.');
        }


    /**
     * Hapus Ruangan
     */
    public function destroyRoom(Room $room)
    {
        $room->delete();

        return redirect()
            ->back()
            ->with('success', 'Ruangan berhasil dihapus.');
    }
}