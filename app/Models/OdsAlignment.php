<?php

/**
 * Modelo Eloquent que representa alineaciones con ODS, define campos editables y relaciones con otras tablas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OdsAlignment extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending' => 'Pendiente',
        'validated' => 'Validada',
        'rejected' => 'Rechazada',
    ];

    protected $fillable = [
        'institutional_objective_id',
        'sdg_id',
        'target_reference',
        'contribution_level',
        'justification',
        'status',
        'observations',
    ];

    // Relaciona el registro con su objetivo institucional.
    public function institutionalObjective(): BelongsTo
    {
        return $this->belongsTo(InstitutionalObjective::class);
    }

    // Relaciona la alineacion con el ODS.
    public function sdg(): BelongsTo
    {
        return $this->belongsTo(Sdg::class);
    }
}
