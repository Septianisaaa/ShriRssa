<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Admission extends Model
{
    protected $fillable = [
        'patient_id',
        'current_room_id',
        'initial_room_id',
        'room_class',
        'admission_date',
        'admission_type',
        'status',
        'discharge_date',
        'discharge_condition',
        'length_of_stay',
        'diagnosis',
    ];

    protected $casts = [
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function currentRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'current_room_id');
    }

    public function initialRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'initial_room_id');
    }

    public function transfers(): HasMany
    {
        return $this->hasMany(PatientTransfer::class);
    }

    /**
     * Hitung Lama Dirawat (LD) secara otomatis:
     * - Pasien KRS - Tanggal MRS
     * - Pasien One Day Care (MRS & KRS di hari yang sama) LD = 1 hari (bukan 0)
     * - Mendukung LD Lintas Bulan (Carried Over)
     */
    public function calculateLengthOfStay(): int
    {
        if (!$this->discharge_date) {
            return 0;
        }

        $admissionDay = Carbon::parse($this->admission_date)->startOfDay();
        $dischargeDay = Carbon::parse($this->discharge_date)->startOfDay();

        $diffInDays = (int) $admissionDay->diffInDays($dischargeDay);

        // One Day Care (Masuk & Keluar di hari sama) -> LD = 1 hari
        return ($diffInDays === 0) ? 1 : $diffInDays;
    }

    /**
     * Menentukan otomatis status meninggal (< 48 jam vs >= 48 jam) berdasarkan tanggal/jam MRS & KRS
     */
    public function determineDeceasedCategory($dischargeDateTime): string
    {
        $admissionTime = Carbon::parse($this->admission_date);
        $dischargeTime = Carbon::parse($dischargeDateTime);

        $hours = $admissionTime->diffInHours($dischargeTime);

        return ($hours < 48) ? 'deceased_under_48h' : 'deceased_over_48h';
    }
}
