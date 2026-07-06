<?php

/**
 * Modelo Eloquent que representa alineaciones con el PND, define campos editables y relaciones con otras tablas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PndAlignment extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending' => 'Pendiente',
        'validated' => 'Validada',
        'rejected' => 'Rechazada',
    ];

    protected $fillable = [
        'institutional_objective_id',
        'pnd_objective_id',
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

    // Relaciona la alineacion con el objetivo PND.
    public function pndObjective(): BelongsTo
    {
        return $this->belongsTo(PndObjective::class);
    }
}
