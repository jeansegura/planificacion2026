<?php

/**
 * Modelo Eloquent que representa planes estrategicos, define campos editables y relaciones con otras tablas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

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

    // Relaciona el plan con su responsable.
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }
}
