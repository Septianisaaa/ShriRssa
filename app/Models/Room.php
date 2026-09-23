<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'name',
        'code',
        'category',
        'room_class',
        'capacity',
        'is_active',
    ];

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class, 'current_room_id');
    }

    public function dailyCensuses(): HasMany
    {
        return $this->hasMany(DailyCensus::class);
    }
}
