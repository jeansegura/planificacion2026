<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sdg extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'description',
        'status',
    ];

    public function alignments(): HasMany
    {
        return $this->hasMany(OdsAlignment::class);
    }
}
