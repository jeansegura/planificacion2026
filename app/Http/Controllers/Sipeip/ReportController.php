<?php

namespace App\Http\Controllers\Sipeip;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Indicator;
use App\Models\InstitutionalGoal;
use App\Models\InstitutionalObjective;
use App\Models\InvestmentProject;
use App\Models\PublicEntity;
use App\Models\StrategicPlan;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('sipeip.reports.index', [
            'datasets' => $this->datasets(),
        ]);
    }

    public function export(string $dataset, string $format): Response
    {
        abort_unless(array_key_exists($dataset, $this->datasets()), 404);
        abort_unless(in_array($format, ['csv', 'json'], true), 404);

        $rows = $this->rows($dataset);
        $filename = $dataset.'-'.now()->format('Ymd-His').'.'.$format;

        if ($format === 'json') {
            return response($rows->values()->toJson(JSON_PRETTY_PRINT), 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);
        }

        $csv = $this->toCsv($rows);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    private function datasets(): array
    {
        return [
            'plans' => 'Planes estrategicos',
            'objectives' => 'Objetivos institucionales',
            'goals' => 'Metas institucionales',
            'indicators' => 'Indicadores',
            'projects' => 'Proyectos de inversion',
            'entities' => 'Entidades publicas',
            'audit' => 'Auditoria',
        ];
    }

    private function rows(string $dataset): Collection
    {
        return match ($dataset) {
            'plans' => StrategicPlan::select('code', 'name', 'institution', 'period_start', 'period_end', 'status')->get(),
            'objectives' => InstitutionalObjective::select('code', 'name', 'institution', 'status')->get(),
            'goals' => InstitutionalGoal::select('code', 'name', 'period_year', 'target_value', 'unit', 'status')->get(),
            'indicators' => Indicator::select('code', 'name', 'unit', 'periodicity', 'baseline_value', 'target_value', 'current_value', 'status')->get(),
            'projects' => InvestmentProject::select('code', 'name', 'intervention_type', 'budget', 'status')->get(),
            'entities' => PublicEntity::select('code', 'name', 'acronym', 'government_level', 'sector', 'subsector', 'status')->get(),
            'audit' => AuditLog::select('module', 'action', 'description', 'ip_address', 'created_at')->latest()->limit(1000)->get(),
        };
    }

    private function toCsv(Collection $rows): string
    {
        if ($rows->isEmpty()) {
            return '';
        }

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, array_keys($rows->first()->toArray()));

        foreach ($rows as $row) {
            fputcsv($handle, $row->toArray());
        }

        rewind($handle);

        return stream_get_contents($handle) ?: '';
    }
}
