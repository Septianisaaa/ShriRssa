<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyCensus extends Model
{
    protected $table = 'daily_censuses';

    protected $fillable = [
        'room_id',
        'census_date',
        'initial_patients',
        'admissions_count',
        'transfers_in_count',
        'transfers_out_count',
        'discharges_count',
        'discharges_cured_count',
        'discharges_aps_count',
        'discharges_run_count',
        'discharges_referred_count',
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
