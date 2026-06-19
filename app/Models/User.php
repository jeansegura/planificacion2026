<?php

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

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function publicEntity(): BelongsTo
    {
        return $this->belongsTo(PublicEntity::class);
    }

    public function strategicPlans(): HasMany
    {
        return $this->hasMany(StrategicPlan::class, 'responsible_user_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function hasPermission(string $permission): bool
    {
        if ((int) $this->id === 1) {
            return true;
        }

        return in_array($permission, $this->role?->permissions ?? [], true);
    }
}
