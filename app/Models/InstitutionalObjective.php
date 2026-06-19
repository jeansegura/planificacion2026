<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstitutionalObjective extends Model
{
    use HasFactory;

    public const STATUSES = [
        'draft' => 'Borrador',
        'review' => 'En revision',
        'approved' => 'Aprobado',
        'returned' => 'Devuelto',
        'inactive' => 'Inactivo',
    ];

    protected $fillable = [
        'strategic_plan_id',
        'code',
        'name',
        'institution',
        'description',
        'baseline',
        'expected_result',
        'status',
        'observations',
    ];

    public function strategicPlan(): BelongsTo
    {
        return $this->belongsTo(StrategicPlan::class);
    }

    public function pndAlignments(): HasMany
    {
        return $this->hasMany(PndAlignment::class);
    }

    public function odsAlignments(): HasMany
    {
        return $this->hasMany(OdsAlignment::class);
    }
}
