<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Indicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'institutional_goal_id',
        'institutional_objective_id',
        'code',
        'name',
        'formula',
        'unit',
        'periodicity',
        'baseline_value',
        'target_value',
        'current_value',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'baseline_value' => 'decimal:2',
            'target_value' => 'decimal:2',
            'current_value' => 'decimal:2',
        ];
    }

    public function institutionalGoal(): BelongsTo
    {
        return $this->belongsTo(InstitutionalGoal::class);
    }

    public function institutionalObjective(): BelongsTo
    {
        return $this->belongsTo(InstitutionalObjective::class);
    }

    public function progress(): float
    {
        if (! $this->target_value || (float) $this->target_value === 0.0) {
            return 0.0;
        }

        return round(((float) $this->current_value / (float) $this->target_value) * 100, 2);
    }
}
