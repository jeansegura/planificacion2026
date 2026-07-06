<?php

/**
 * Modelo Eloquent que representa expediente documental de proyectos, define campos editables y relaciones con otras tablas.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDocument extends Model
{
    use HasFactory;

    public const TYPES = [
        'profile' => 'Perfil del proyecto',
        'technical_report' => 'Informe tecnico',
        'budget' => 'Presupuesto',
        'feasibility' => 'Estudio de viabilidad',
        'support' => 'Documento de soporte',
        'other' => 'Otro',
    ];

    protected $fillable = [
        'investment_project_id',
        'uploaded_by',
        'type',
        'description',
        'file_path',
        'original_name',
        'mime_type',
        'size',
    ];

    // Ejecuta la accion principal de este bloque.
    public function investmentProject(): BelongsTo
    {
        return $this->belongsTo(InvestmentProject::class);
    }

    // Relaciona el documento con el usuario que lo subio.
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
