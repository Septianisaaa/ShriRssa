<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'rm_number',
        'name',
        'gender',
        'birth_date',
        'address',
        'phone',
    ];

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }
}
