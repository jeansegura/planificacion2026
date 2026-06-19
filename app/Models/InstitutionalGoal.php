<?php

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

    public function institutionalObjective(): BelongsTo
    {
        return $this->belongsTo(InstitutionalObjective::class);
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class);
    }
}
