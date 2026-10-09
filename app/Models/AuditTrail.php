<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditTrail extends Model
{
    protected $table = 'audit_trails';

    protected $fillable = [
        'user_id',
        'username',
        'user_name',
        'action',
        'module',
        'description',
        'old_values',
        'new_values',
        'method',
        'url',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'user_id'
        );
    }
}