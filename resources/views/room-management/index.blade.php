@extends('layouts.app')

@section('title', 'Kelola Ruangan - SHRI RSSA')
@section('header_title', 'Kelola Ruangan')
@section('header_subtitle', 'Pengelolaan data ruangan, instalasi, kelas perawatan, dan kapasitas tempat tidur RSUD Dr. Saiful Anwar')

@section('content')

<div style="
    display: grid;
    grid-template-columns: 350px minmax(0, 1fr);
    gap: 1rem;
    align-items: start;
">

    {{-- ========================================================= --}}
    {{-- KOLOM KIRI --}}
    {{-- ========================================================= --}}
    <div style="
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    ">

        {{-- ===================================================== --}}
        {{-- TAMBAH INSTALASI --}}
        {{-- ===================================================== --}}
        <div class="card">

            <h3 class="section-title">
                Tambah Instalasi
            </h3>

            <form
                action="{{ route('shri.room_management.store_installation') }}"
                method="POST"
            >
                @csrf

                <div class="form-group">

                    <label class="form-label">
                        Nama Instalasi:
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="Contoh: IRNA 1"
                        value="{{ old('name') }}"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                    style="
                        width: 100%;
                        justify-content: center;
                    "
                >
                    <i class="fa-solid fa-plus"></i>
                    Tambahkan Instalasi
                </button>

            </form>

        </div>


        {{-- ===================================================== --}}
        {{-- TAMBAH KELAS PERAWATAN --}}
        {{-- ===================================================== --}}
        <div class="card">

            <h3 class="section-title">
                Tambah Kelas Perawatan
            </h3>

            <form
                action="{{ route('shri.room_management.store_room_class') }}"
                method="POST"
            >
                @csrf

                <div class="form-group">

                    <label class="form-label">
                        Nama Kelas Perawatan
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="Contoh: VVIP"
                        value="{{ old('name') }}"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                    style="
                        width: 100%;
                        justify-content: center;
                    "
                >
                    <i class="fa-solid fa-plus"></i>
                    Tambahkan Kelas Perawatan
                </button>

            </form>

        </div>


        {{-- ===================================================== --}}
        {{-- TAMBAH RUANGAN --}}
        {{-- ===================================================== --}}
        <div class="card">

            <h3 class="section-title">
                Tambah Ruangan
            </h3>

            <form
                action="{{ route('shri.room_management.store') }}"
                method="POST"
            >
                @csrf

                {{-- Nama Ruangan --}}
                <div class="form-group">

                    <label class="form-label">
                        Nama Ruangan:
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="Contoh: Mawar"
                        value="{{ old('name') }}"
                        required
                    >

                </div>


                {{-- Kode Ruangan --}}
                <div class="form-group">

                    <label class="form-label">
                        Kode Ruangan:
                    </label>

                    <input
                        type="text"
                        name="code"
                        class="form-control"
                        placeholder="Contoh: MW01"
                        value="{{ old('code') }}"
                        required
                    >

                </div>


                {{-- Instalasi --}}
                <div class="form-group">

                    <label class="form-label">
                        Instalasi:
                    </label>

                    <select
                        name="installation"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Pilih Instalasi --
                        </option>

                        @foreach($installations as $installation)

                            <option
                                value="{{ $installation->name }}"
                                {{ old('installation') == $installation->name ? 'selected' : '' }}
                            >
                                {{ $installation->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Kelas Perawatan --}}
                <div class="form-group">

                    <label class="form-label">
                        Kelas Perawatan:
                    </label>

                    <select
                        name="room_class"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Pilih Kelas Perawatan --
                        </option>

                        @foreach($roomClasses as $roomClass)

                            <option
                                value="{{ $roomClass->name }}"
                                {{ old('room_class') == $roomClass->name ? 'selected' : '' }}
                            >
                                {{ $roomClass->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Kapasitas --}}
                <div class="form-group">

                    <label class="form-label">
                        Kapasitas Tempat Tidur (TT):
                    </label>

                    <input
                        type="number"
                        name="capacity"
                        class="form-control"
                        placeholder="Contoh: 30"
                        min="1"
                        value="{{ old('capacity', 10) }}"
                        required
                    >

                    <small style="
                        color: #64748b;
                        font-size: 0.725rem;
                        margin-top: 0.25rem;
                        display: block;
                    ">
                        Masukkan jumlah tempat tidur yang tersedia pada ruangan.
                    </small>

                </div>


                {{-- Tombol --}}
                <button
                    type="submit"
                    class="btn btn-primary"
                    style="
                        width: 100%;
                        justify-content: center;
                    "
                >
                    <i class="fa-solid fa-plus"></i>
                    Tambahkan Ruangan
                </button>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- KOLOM KANAN --}}
    {{-- ========================================================= --}}
    <div class="card">

        <h3 class="section-title">
            Daftar Ruangan ({{ $rooms->count() }})
        </h3>


        <div class="table-responsive">

            <table>

                <thead>

                    <tr>

                        <th>
                            Ruangan
                        </th>

                        <th>
                            Kode
                        </th>

                        <th>
                            Instalasi
                        </th>

                        <th>
                            Kelas
                        </th>

                        <th>
                            TT
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($rooms as $room)

                        <tr>

                            {{-- Ruangan --}}
                            <td>

                                <strong>
                                    {{ $room->name }}
                                </strong>

                            </td>


                            {{-- Kode --}}
                            <td>

                                <code>
                                    {{ $room->code }}
                                </code>

                            </td>


                            {{-- Instalasi --}}
                            <td>

                                {{ $room->installation }}

                            </td>


                            {{-- Kelas --}}
                            <td>

                                {{ $room->room_class }}

                            </td>


                            {{-- Kapasitas --}}
                            <td>

                                <strong>
                                    {{ $room->capacity }}
                                </strong>

                                TT

                            </td>


                            {{-- Aksi --}}
                            <td>

                                <div style="
                                    display: flex;
                                    gap: 0.4rem;
                                ">

                                    {{-- EDIT --}}
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        style="
                                            padding: 0.3rem 0.6rem;
                                            font-size: 0.725rem;
                                        "
                                        onclick='openEditRoomModal(@json($room))'
                                    >

                                        <i class="fa-solid fa-pen"></i>

                                        Edit

                                    </button>


                                    {{-- HAPUS --}}
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        style="
                                            padding: 0.3rem 0.6rem;
                                            font-size: 0.725rem;
                                            color: var(--danger-text);
                                            border-color: var(--danger-border);
                                            background: var(--danger-bg);
                                        "
                                        onclick="deleteRoom({{ $room->id }})"
                                    >

                                        <i class="fa-solid fa-trash"></i>

                                        Hapus

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                style="
                                    text-align: center;
                                    padding: 2rem;
                                    color: var(--text-muted);
                                "
                            >

                                <i
                                    class="fa-solid fa-hospital"
                                    style="
                                        font-size: 1.5rem;
                                        margin-bottom: 0.5rem;
                                    "
                                ></i>

                                <div>
                                    Belum ada data ruangan.
                                </div>

                                <small>
                                    Silakan tambahkan ruangan menggunakan form di samping.
                                </small>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection


{{-- ========================================================= --}}
{{-- MODAL EDIT RUANGAN --}}
{{-- ========================================================= --}}

<div
    id="editRoomModal"
    style="
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    "
>

    <div
        style="
            width: 100%;
            max-width: 600px;
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        "
    >

        {{-- Header --}}
        <div style="
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        ">

            <h2 style="margin: 0;">
                Edit Ruangan
            </h2>

            <button
                type="button"
                onclick="closeEditRoomModal()"
                style="
                    border: none;
                    background: none;
                    font-size: 24px;
                    cursor: pointer;
                    color: #777;
                "
            >
                &times;
            </button>

        </div>


        {{-- Form --}}
        <form
            id="editRoomForm"
            method="POST"
        >

            @csrf
            @method('PUT')


            {{-- Nama --}}
            <div style="margin-bottom: 16px;">

                <label style="
                    display: block;
                    margin-bottom: 7px;
                    font-weight: 600;
                ">
                    Nama Ruangan
                </label>

                <input
                    type="text"
                    id="edit_name"
                    name="name"
                    required
                    style="
                        width: 100%;
                        padding: 11px;
                        border: 1px solid #ccd5df;
                        border-radius: 7px;
                    "
                >

            </div>


            {{-- Kode --}}
            <div style="margin-bottom: 16px;">

                <label style="
                    display: block;
                    margin-bottom: 7px;
                    font-weight: 600;
                ">
                    Kode Ruangan
                </label>

                <input
                    type="text"
                    id="edit_code"
                    name="code"
                    required
                    style="
                        width: 100%;
                        padding: 11px;
                        border: 1px solid #ccd5df;
                        border-radius: 7px;
                    "
                >

            </div>


            {{-- Instalasi --}}
            <div style="margin-bottom: 16px;">

                <label style="
                    display: block;
                    margin-bottom: 7px;
                    font-weight: 600;
                ">
                    Instalasi
                </label>

                <select
                    id="edit_installation"
                    name="installation"
                    required
                    style="
                        width: 100%;
                        padding: 11px;
                        border: 1px solid #ccd5df;
                        border-radius: 7px;
                    "
                >

                    @foreach($installations as $installation)

                        <option value="{{ $installation->name }}">
                            {{ $installation->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Kelas --}}
            <div style="margin-bottom: 16px;">

                <label style="
                    display: block;
                    margin-bottom: 7px;
                    font-weight: 600;
                ">
                    Kelas Perawatan
                </label>

                <select
                    id="edit_room_class"
                    name="room_class"
                    required
                    style="
                        width: 100%;
                        padding: 11px;
                        border: 1px solid #ccd5df;
                        border-radius: 7px;
                    "
                >

                    @foreach($roomClasses as $roomClass)

                        <option value="{{ $roomClass->name }}">
                            {{ $roomClass->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Kapasitas --}}
            <div style="margin-bottom: 16px;">

                <label style="
                    display: block;
                    margin-bottom: 7px;
                    font-weight: 600;
                ">
                    Kapasitas Tempat Tidur (TT)
                </label>

                <input
                    type="number"
                    id="edit_capacity"
                    name="capacity"
                    min="0"
                    required
                    style="
                        width: 100%;
                        padding: 11px;
                        border: 1px solid #ccd5df;
                        border-radius: 7px;
                    "
                >

            </div>


            {{-- Tombol --}}
            <div style="
                display: flex;
                justify-content: flex-end;
                gap: 10px;
            ">

                <button
                    type="button"
                    onclick="closeEditRoomModal()"
                    style="
                        padding: 10px 18px;
                        border: 1px solid #ccd5df;
                        background: white;
                        border-radius: 7px;
                        cursor: pointer;
                    "
                >
                    Batal
                </button>

                <button
                    type="submit"
                    style="
                        padding: 10px 18px;
                        border: none;
                        background: #efd365;
                        color: #111827;
                        border-radius: 7px;
                        font-weight: 600;
                        cursor: pointer;
                    "
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

<script>

function openEditRoomModal(room)
{
    document.getElementById('edit_name').value =
        room.name ?? '';

    document.getElementById('edit_code').value =
        room.code ?? '';

    document.getElementById('edit_installation').value =
        room.installation ?? '';

    document.getElementById('edit_room_class').value =
        room.room_class ?? '';

    document.getElementById('edit_capacity').value =
        room.capacity ?? 0;

    document.getElementById('editRoomForm').action =
        "{{ route('shri.room_management.update', ['room' => '__ID__']) }}"
            .replace('__ID__', room.id);

    document.getElementById('editRoomModal').style.display = 'flex';
}


function closeEditRoomModal()
{
    document.getElementById('editRoomModal').style.display = 'none';
}


function deleteRoom(id)
{
    if (!confirm('Apakah Anda yakin ingin menghapus ruangan ini?')) {
        return;
    }

    const form = document.createElement('form');

    form.method = 'POST';

    form.action =
        "{{ route('shri.room_management.destroy', ['room' => '__ID__']) }}"
            .replace('__ID__', id);

    form.innerHTML = `
        @csrf
        @method('DELETE')
    `;

    document.body.appendChild(form);

    form.submit();
}

</script>
```
