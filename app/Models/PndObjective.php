<?php

/**
 * Modelo Eloquent que representa objetivos del PND, define campos editables y relaciones con otras tablas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PndObjective extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'axis',
        'name',
        'policy',
        'description',
        'status',
    ];

    // Obtiene las alineaciones asociadas al catalogo.
    public function alignments(): HasMany
    {
        return $this->hasMany(PndAlignment::class);
    }
}
