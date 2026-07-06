<?php

/**
 * Modelo Eloquent que representa roles y permisos, define campos editables y relaciones con otras tablas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'permissions', 'status'];

    protected function casts(): array
    {
        return ['permissions' => 'array'];
    }

    // Ejecuta la accion principal de este bloque.
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Filtra solo registros activos.
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
