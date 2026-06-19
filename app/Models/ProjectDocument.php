<?php

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

    public function investmentProject(): BelongsTo
    {
        return $this->belongsTo(InvestmentProject::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
