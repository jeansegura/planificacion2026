<?php

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

    public function institutionalObjective(): BelongsTo
    {
        return $this->belongsTo(InstitutionalObjective::class);
    }

    public function sdg(): BelongsTo
    {
        return $this->belongsTo(Sdg::class);
    }
}
