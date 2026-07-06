<?php

/**
 * Modelo Eloquent que representa proyectos de inversion, define campos editables y relaciones con otras tablas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvestmentProject extends Model
{
    use HasFactory;

    public const STATUSES = [
        'draft' => 'Borrador',
        'review' => 'En revision',
        'prioritized' => 'Priorizado',
        'observed' => 'Observado',
        'rejected' => 'Rechazado',
        'closed' => 'Cerrado',
    ];

    protected $fillable = [
        'public_entity_id',
        'institutional_objective_id',
        'code',
        'name',
        'intervention_type',
        'budget',
        'start_date',
        'end_date',
        'status',
        'description',
        'observations',
    ];

    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    // Relaciona el usuario con su entidad publica.
    public function publicEntity(): BelongsTo
    {
        return $this->belongsTo(PublicEntity::class);
    }

    // Relaciona el registro con su objetivo institucional.
    public function institutionalObjective(): BelongsTo
    {
        return $this->belongsTo(InstitutionalObjective::class);
    }

    // Obtiene los documentos del expediente.
    public function documents(): HasMany
    {
        return $this->hasMany(ProjectDocument::class);
    }
}
