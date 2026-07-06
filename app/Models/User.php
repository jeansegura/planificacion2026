<?php

/**
 * Modelo Eloquent que representa usuarios institucionales, define campos editables y relaciones con otras tablas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'identification',
        'institution',
        'position',
        'phone',
        'status',
        'role_id',
        'public_entity_id',
        'user_type',
        'organizational_unit',
        'auth_provider',
        'sso_subject',
        'must_change_password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'deactivated_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    // Relaciona el usuario con su rol institucional.
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    // Relaciona el usuario con su entidad publica.
    public function publicEntity(): BelongsTo
    {
        return $this->belongsTo(PublicEntity::class);
    }

    // Obtiene los planes estrategicos vinculados.
    public function strategicPlans(): HasMany
    {
        return $this->hasMany(StrategicPlan::class, 'responsible_user_id');
    }

    // Obtiene los eventos de auditoria del usuario.
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // Comprueba si el usuario esta activo.
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // Verifica si el usuario tiene un permiso especifico.
    public function hasPermission(string $permission): bool
    {
        if ((int) $this->id === 1) {
            return true;
        }

        return in_array($permission, $this->role?->permissions ?? [], true);
    }
}
