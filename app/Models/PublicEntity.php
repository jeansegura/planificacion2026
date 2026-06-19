<?php

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

    public function investmentProjects(): HasMany
    {
        return $this->hasMany(InvestmentProject::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
