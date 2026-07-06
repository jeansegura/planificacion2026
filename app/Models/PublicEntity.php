<?php

/**
 * Modelo Eloquent que representa entidades publicas, define campos editables y relaciones con otras tablas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PublicEntity extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'acronym',
        'government_level',
        'macro_sector',
        'sector',
        'subsector',
        'status',
    ];

    // Obtiene los proyectos de inversion de la entidad.
    public function investmentProjects(): HasMany
    {
        return $this->hasMany(InvestmentProject::class);
    }

    // Filtra solo registros activos.
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
