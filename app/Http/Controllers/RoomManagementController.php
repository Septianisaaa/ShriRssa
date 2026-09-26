<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('room_class', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        if ($request->filled('room_class')) {
            $query->where('room_class', $request->get('room_class'));
        }

        $rooms = $query->orderBy('category')->orderBy('name')->get();

        // Ambil daftar unik Instalasi & Kelas Perawatan yang sudah ada di DB
        $existingCategories = Room::distinct()->pluck('category')->filter()->values();
        $existingClasses = Room::distinct()->pluck('room_class')->filter()->values();

        $defaultClasses = collect(['VVIP', 'VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3', 'Non Kelas / Khusus']);
        $allClasses = $defaultClasses->concat($existingClasses)->unique()->values();

        return view('rooms.index', compact('rooms', 'existingCategories', 'allClasses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:rooms,code',
            'category_select' => 'nullable|string',
            'category_custom' => 'nullable|string|max:255',
            'room_class_select' => 'nullable|string',
            'room_class_custom' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $category = !empty($request->category_custom) ? trim($request->category_custom) : $request->category_select;
        $roomClass = !empty($request->room_class_custom) ? trim($request->room_class_custom) : $request->room_class_select;

        if (empty($category)) {
            return redirect()->back()->withErrors(['category_select' => 'Instalasi (Kategori Ruangan) wajib dipilih atau diisi.'])->withInput();
        }

        if (empty($roomClass)) {
            return redirect()->back()->withErrors(['room_class_select' => 'Kelas Perawatan wajib dipilih atau diisi.'])->withInput();
        }

        Room::create([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'category' => $category,
            'room_class' => $roomClass,
            'capacity' => $validated['capacity'],
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('shri.rooms.index')->with('success', 'Ruangan / Instalasi / Kelas Perawatan baru berhasil ditambahkan!');
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('rooms')->ignore($room->id)],
            'category' => 'required|string|max:255',
            'room_class' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $room->update([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'category' => $validated['category'],
            'room_class' => $validated['room_class'],
            'capacity' => $validated['capacity'],
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('shri.rooms.index')->with('success', "Data Ruangan {$room->name} berhasil diperbarui!");
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('shri.rooms.index')->with('success', "Ruangan {$room->name} berhasil dihapus!");
    }
}
