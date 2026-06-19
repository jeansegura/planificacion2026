<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrategicPlan extends Model
{
    use HasFactory;

    public const STATUSES = [
        'draft' => 'Borrador',
        'review' => 'En revision',
        'approved' => 'Aprobado',
        'returned' => 'Devuelto',
        'archived' => 'Archivado',
    ];

    protected $fillable = [
        'code',
        'name',
        'institution',
        'period_start',
        'period_end',
        'description',
        'objectives',
        'goals',
        'status',
        'responsible_user_id',
        'observations',
    ];

    protected function casts(): array
    {
        return [
            'objectives' => 'array',
            'goals' => 'array',
        ];
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }
}
