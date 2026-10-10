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

    /**
     * Helper statis untuk mencatat audit trail secara terpusat.
     */
    public static function record(
        string $action,
        string $module,
        string $description,
        ?User $user = null,
        ?array $newValues = null,
        ?array $oldValues = null,
        ?\Illuminate\Http\Request $request = null
    ): ?self {
        try {
            $req = $request ?? request();
            $authUser = $user ?? \Illuminate\Support\Facades\Auth::user();

            return self::create([
                'user_id' => $authUser?->id,
                'username' => $authUser?->username ?? $authUser?->email ?? 'Sistem',
                'user_name' => $authUser?->name ?? 'Sistem',
                'action' => strtolower($action),
                'module' => $module,
                'description' => $description,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'method' => $req ? strtoupper($req->method()) : null,
                'url' => $req ? $req->path() : null,
                'ip_address' => $req ? $req->ip() : null,
                'user_agent' => $req ? \Illuminate\Support\Str::limit((string) $req->userAgent(), 1000) : null,
            ]);
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    /**
     * Badge CSS class untuk aksi.
     */
    public function getActionBadgeClassAttribute(): string
    {
        return match (strtolower($this->action ?? '')) {
            'create', 'created', 'tambah', 'store' => 'badge-action-create',
            'update', 'updated', 'ubah', 'edit' => 'badge-action-update',
            'delete', 'deleted', 'hapus', 'destroy' => 'badge-action-delete',
            'login' => 'badge-action-login',
            'logout' => 'badge-action-logout',
            default => 'badge-action-default',
        };
    }

    /**
     * Label aksi dalam Bahasa Indonesia.
     */
    public function getActionLabelAttribute(): string
    {
        return match (strtolower($this->action ?? '')) {
            'create', 'created', 'tambah', 'store' => 'Tambah Data',
            'update', 'updated', 'ubah', 'edit' => 'Ubah Data',
            'delete', 'deleted', 'hapus', 'destroy' => 'Hapus Data',
            'login' => 'Masuk (Login)',
            'logout' => 'Keluar (Logout)',
            default => ucfirst($this->action ?? 'Aktivitas'),
        };
    }

    /**
     * Icon FontAwesome untuk aksi.
     */
    public function getActionIconAttribute(): string
    {
        return match (strtolower($this->action ?? '')) {
            'create', 'created', 'tambah', 'store' => 'fa-solid fa-plus-circle',
            'update', 'updated', 'ubah', 'edit' => 'fa-solid fa-pen-to-square',
            'delete', 'deleted', 'hapus', 'destroy' => 'fa-solid fa-trash-can',
            'login' => 'fa-solid fa-right-to-bracket',
            'logout' => 'fa-solid fa-right-from-bracket',
            default => 'fa-solid fa-circle-dot',
        };
    }

    /**
     * Icon FontAwesome untuk modul.
     */
    public function getModuleIconAttribute(): string
    {
        $mod = strtolower($this->module ?? '');
        return match (true) {
            str_contains($mod, 'sensus') || str_contains($mod, 'pasien') => 'fa-solid fa-hospital-user',
            str_contains($mod, 'mutasi') || str_contains($mod, 'transfer') => 'fa-solid fa-right-left',
            str_contains($mod, 'ruangan') || str_contains($mod, 'room') => 'fa-solid fa-hospital',
            str_contains($mod, 'akun') || str_contains($mod, 'user') => 'fa-solid fa-users-gear',
            str_contains($mod, 'profil') || str_contains($mod, 'profile') => 'fa-solid fa-user-gear',
            str_contains($mod, 'autentikasi') || str_contains($mod, 'auth') => 'fa-solid fa-shield-halved',
            default => 'fa-solid fa-layer-group',
        };
    }
}