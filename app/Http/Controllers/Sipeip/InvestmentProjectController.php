<?php

namespace App\Http\Controllers\Sipeip;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\InstitutionalObjective;
use App\Models\InvestmentProject;
use App\Models\ProjectDocument;
use App\Models\PublicEntity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvestmentProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = InvestmentProject::query()
            ->with('publicEntity', 'institutionalObjective')
            ->withCount('documents')
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('sipeip.investment-projects.index', [
            'projects' => $projects,
            'statuses' => InvestmentProject::STATUSES,
        ]);
    }

    public function create(): View
    {
        return view('sipeip.investment-projects.create', [
            'project' => new InvestmentProject(['status' => 'draft']),
            'entities' => PublicEntity::orderBy('name')->get(),
            'objectives' => InstitutionalObjective::orderBy('code')->get(),
            'statuses' => InvestmentProject::STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $project = InvestmentProject::create($data);
        AuditLog::record('Proyectos de inversion', 'crear', $project, $data);

        return redirect()->route('investment-projects.index')->with('status', 'Proyecto de inversion registrado correctamente.');
    }

    public function show(InvestmentProject $investmentProject): View
    {
        $investmentProject->load([
            'publicEntity',
            'institutionalObjective',
            'documents.uploader',
        ]);

        return view('sipeip.investment-projects.show', [
            'project' => $investmentProject,
            'statuses' => InvestmentProject::STATUSES,
            'documentTypes' => ProjectDocument::TYPES,
        ]);
    }

    public function edit(InvestmentProject $investmentProject): View
    {
        return view('sipeip.investment-projects.edit', [
            'project' => $investmentProject,
            'entities' => PublicEntity::orderBy('name')->get(),
            'objectives' => InstitutionalObjective::orderBy('code')->get(),
            'statuses' => InvestmentProject::STATUSES,
        ]);
    }

    public function update(Request $request, InvestmentProject $investmentProject): RedirectResponse
    {
        $data = $this->validatedData($request, $investmentProject);
        $before = $investmentProject->only(array_keys($data));
        $investmentProject->update($data);

        AuditLog::record('Proyectos de inversion', 'actualizar', $investmentProject, [
            'before' => $before,
            'after' => $investmentProject->only(array_keys($data)),
        ]);

        return redirect()->route('investment-projects.index')->with('status', 'Proyecto de inversion actualizado correctamente.');
    }

    public function storeDocument(Request $request, InvestmentProject $investmentProject): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(ProjectDocument::TYPES))],
            'description' => ['nullable', 'string', 'max:1000'],
            'document' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png'],
        ]);

        $file = $data['document'];
        $path = $file->store('investment-project-documents');

        $document = $investmentProject->documents()->create([
            'uploaded_by' => $request->user()?->id,
            'type' => $data['type'],
            'description' => $data['description'] ?? null,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        AuditLog::record('Expediente de proyectos', 'subir_documento', $document, [
            'project' => $investmentProject->only(['id', 'code', 'name']),
            'document' => $document->only(['type', 'original_name', 'size']),
        ]);

        return redirect()
            ->route('investment-projects.show', $investmentProject)
            ->with('status', 'Documento cargado al expediente del proyecto.');
    }

    public function downloadDocument(InvestmentProject $investmentProject, ProjectDocument $document): StreamedResponse
    {
        abort_unless($document->investment_project_id === $investmentProject->id, 404);

        return Storage::download($document->file_path, $document->original_name);
    }

    public function destroyDocument(InvestmentProject $investmentProject, ProjectDocument $document): RedirectResponse
    {
        abort_unless($document->investment_project_id === $investmentProject->id, 404);

        Storage::delete($document->file_path);
        $snapshot = $document->only(['type', 'original_name', 'size']);
        $document->delete();

        AuditLog::record('Expediente de proyectos', 'eliminar_documento', $investmentProject, [
            'document' => $snapshot,
        ]);

        return redirect()
            ->route('investment-projects.show', $investmentProject)
            ->with('status', 'Documento eliminado del expediente.');
    }

    private function validatedData(Request $request, ?InvestmentProject $project = null): array
    {
        return $request->validate([
            'public_entity_id' => ['nullable', 'exists:public_entities,id'],
            'institutional_objective_id' => ['nullable', 'exists:institutional_objectives,id'],
            'code' => ['required', 'string', 'max:50', Rule::unique('investment_projects')->ignore($project?->id)],
            'name' => ['required', 'string', 'max:255'],
            'intervention_type' => ['nullable', 'string', 'max:150'],
            'budget' => ['required', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(array_keys(InvestmentProject::STATUSES))],
            'description' => ['nullable', 'string'],
            'observations' => ['nullable', 'string'],
        ]);
    }
}
