<?php

/**
 * Modelo Eloquent que representa metas institucionales, define campos editables y relaciones con otras tablas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstitutionalGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'institutional_objective_id',
        'code',
        'name',
        'period_year',
        'target_value',
        'unit',
        'responsible',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return ['target_value' => 'decimal:2'];
    }

    // Relaciona el registro con su objetivo institucional.
    public function institutionalObjective(): BelongsTo
    {
        return $this->belongsTo(InstitutionalObjective::class);
    }

    // Obtiene los indicadores de la meta.
    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class);
    }
}
