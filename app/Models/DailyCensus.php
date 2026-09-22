<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyCensus extends Model
{
    protected $fillable = [
        'room_id',
        'census_date',
        'initial_patients',
        'admissions_count',
        'transfers_in_count',
        'transfers_out_count',
        'discharges_count',
        'deaths_under_48h',
        'deaths_over_48h',
        'remaining_patients',
        'care_days',
        'total_length_of_stay',
    ];

    protected $casts = [
        'census_date' => 'date',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
