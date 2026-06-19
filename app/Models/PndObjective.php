<?php

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

    public function alignments(): HasMany
    {
        return $this->hasMany(PndAlignment::class);
    }
}
