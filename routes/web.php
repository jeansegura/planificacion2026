<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sipeip\AuditLogController;
use App\Http\Controllers\Sipeip\IndicatorController;
use App\Http\Controllers\Sipeip\InstitutionalGoalController;
use App\Http\Controllers\Sipeip\InstitutionalObjectiveController;
use App\Http\Controllers\Sipeip\InstitutionalUserController;
use App\Http\Controllers\Sipeip\InvestmentProjectController;
use App\Http\Controllers\Sipeip\OdsAlignmentController;
use App\Http\Controllers\Sipeip\PndAlignmentController;
use App\Http\Controllers\Sipeip\PndObjectiveController;
use App\Http\Controllers\Sipeip\PublicEntityController;
use App\Http\Controllers\Sipeip\ReportController;
use App\Http\Controllers\Sipeip\RoleController;
use App\Http\Controllers\Sipeip\SdgController;
use App\Http\Controllers\Sipeip\StrategicPlanController;
use App\Models\Indicator;
use App\Models\InstitutionalGoal;
use App\Models\InstitutionalObjective;
use App\Models\InvestmentProject;
use App\Models\OdsAlignment;
use App\Models\PndAlignment;
use App\Models\PublicEntity;
use App\Models\Role;
use App\Models\StrategicPlan;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', [
        'usersCount' => User::count(),
        'rolesCount' => Role::count(),
        'plansCount' => StrategicPlan::count(),
        'objectivesCount' => InstitutionalObjective::count(),
        'pndAlignmentsCount' => PndAlignment::count(),
        'odsAlignmentsCount' => OdsAlignment::count(),
        'goalsCount' => InstitutionalGoal::count(),
        'indicatorsCount' => Indicator::count(),
        'projectsCount' => InvestmentProject::count(),
        'entitiesCount' => PublicEntity::count(),
        'plansByStatus' => StrategicPlan::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status'),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', [InstitutionalUserController::class, 'index'])
        ->middleware('permission:users.view')
        ->name('users.index');
    Route::get('/users/create', [InstitutionalUserController::class, 'create'])
        ->middleware('permission:users.manage')
        ->name('users.create');
    Route::post('/users', [InstitutionalUserController::class, 'store'])
        ->middleware('permission:users.manage')
        ->name('users.store');
    Route::get('/users/{user}/edit', [InstitutionalUserController::class, 'edit'])
        ->middleware('permission:users.manage')
        ->name('users.edit');
    Route::put('/users/{user}', [InstitutionalUserController::class, 'update'])
        ->middleware('permission:users.manage')
        ->name('users.update');
    Route::patch('/users/{user}', [InstitutionalUserController::class, 'update'])
        ->middleware('permission:users.manage');
    Route::patch('/users/{user}/deactivate', [InstitutionalUserController::class, 'deactivate'])
        ->middleware('permission:users.manage')
        ->name('users.deactivate');
    Route::patch('/users/{user}/activate', [InstitutionalUserController::class, 'activate'])
        ->middleware('permission:users.manage')
        ->name('users.activate');

    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:roles.view')
        ->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])
        ->middleware('permission:roles.manage')
        ->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])
        ->middleware('permission:roles.manage')
        ->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])
        ->middleware('permission:roles.manage')
        ->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])
        ->middleware('permission:roles.manage')
        ->name('roles.update');
    Route::patch('/roles/{role}', [RoleController::class, 'update'])
        ->middleware('permission:roles.manage');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
        ->middleware('permission:roles.manage')
        ->name('roles.destroy');

    Route::get('/strategic-plans', [StrategicPlanController::class, 'index'])
        ->middleware('permission:plans.view')
        ->name('strategic-plans.index');
    Route::get('/strategic-plans/create', [StrategicPlanController::class, 'create'])
        ->middleware('permission:plans.manage')
        ->name('strategic-plans.create');
    Route::post('/strategic-plans', [StrategicPlanController::class, 'store'])
        ->middleware('permission:plans.manage')
        ->name('strategic-plans.store');
    Route::get('/strategic-plans/{strategicPlan}', [StrategicPlanController::class, 'show'])
        ->middleware('permission:plans.view')
        ->name('strategic-plans.show');
    Route::get('/strategic-plans/{strategicPlan}/edit', [StrategicPlanController::class, 'edit'])
        ->middleware('permission:plans.manage')
        ->name('strategic-plans.edit');
    Route::put('/strategic-plans/{strategicPlan}', [StrategicPlanController::class, 'update'])
        ->middleware('permission:plans.manage')
        ->name('strategic-plans.update');
    Route::patch('/strategic-plans/{strategicPlan}', [StrategicPlanController::class, 'update'])
        ->middleware('permission:plans.manage');
    Route::patch('/strategic-plans/{strategicPlan}/status', [StrategicPlanController::class, 'changeStatus'])
        ->middleware('permission:plans.validate')
        ->name('strategic-plans.status');

    Route::get('/institutional-objectives', [InstitutionalObjectiveController::class, 'index'])
        ->middleware('permission:objectives.view')
        ->name('institutional-objectives.index');
    Route::get('/institutional-objectives/create', [InstitutionalObjectiveController::class, 'create'])
        ->middleware('permission:objectives.manage')
        ->name('institutional-objectives.create');
    Route::post('/institutional-objectives', [InstitutionalObjectiveController::class, 'store'])
        ->middleware('permission:objectives.manage')
        ->name('institutional-objectives.store');
    Route::get('/institutional-objectives/{institutionalObjective}', [InstitutionalObjectiveController::class, 'show'])
        ->middleware('permission:objectives.view')
        ->name('institutional-objectives.show');
    Route::get('/institutional-objectives/{institutionalObjective}/edit', [InstitutionalObjectiveController::class, 'edit'])
        ->middleware('permission:objectives.manage')
        ->name('institutional-objectives.edit');
    Route::put('/institutional-objectives/{institutionalObjective}', [InstitutionalObjectiveController::class, 'update'])
        ->middleware('permission:objectives.manage')
        ->name('institutional-objectives.update');
    Route::patch('/institutional-objectives/{institutionalObjective}', [InstitutionalObjectiveController::class, 'update'])
        ->middleware('permission:objectives.manage');
    Route::patch('/institutional-objectives/{institutionalObjective}/status', [InstitutionalObjectiveController::class, 'changeStatus'])
        ->middleware('permission:objectives.validate')
        ->name('institutional-objectives.status');

    Route::get('/pnd-objectives', [PndObjectiveController::class, 'index'])
        ->middleware('permission:pnd.view')
        ->name('pnd-objectives.index');
    Route::get('/pnd-objectives/create', [PndObjectiveController::class, 'create'])
        ->middleware('permission:pnd.manage')
        ->name('pnd-objectives.create');
    Route::post('/pnd-objectives', [PndObjectiveController::class, 'store'])
        ->middleware('permission:pnd.manage')
        ->name('pnd-objectives.store');
    Route::get('/pnd-objectives/{pndObjective}/edit', [PndObjectiveController::class, 'edit'])
        ->middleware('permission:pnd.manage')
        ->name('pnd-objectives.edit');
    Route::put('/pnd-objectives/{pndObjective}', [PndObjectiveController::class, 'update'])
        ->middleware('permission:pnd.manage')
        ->name('pnd-objectives.update');

    Route::get('/pnd-alignments', [PndAlignmentController::class, 'index'])
        ->middleware('permission:pnd.view')
        ->name('pnd-alignments.index');
    Route::get('/pnd-alignments/create', [PndAlignmentController::class, 'create'])
        ->middleware('permission:pnd.manage')
        ->name('pnd-alignments.create');
    Route::post('/pnd-alignments', [PndAlignmentController::class, 'store'])
        ->middleware('permission:pnd.manage')
        ->name('pnd-alignments.store');
    Route::get('/pnd-alignments/{pndAlignment}/edit', [PndAlignmentController::class, 'edit'])
        ->middleware('permission:pnd.manage')
        ->name('pnd-alignments.edit');
    Route::put('/pnd-alignments/{pndAlignment}', [PndAlignmentController::class, 'update'])
        ->middleware('permission:pnd.manage')
        ->name('pnd-alignments.update');

    Route::get('/sdgs', [SdgController::class, 'index'])
        ->middleware('permission:ods.view')
        ->name('sdgs.index');
    Route::get('/sdgs/{sdg}/edit', [SdgController::class, 'edit'])
        ->middleware('permission:ods.manage')
        ->name('sdgs.edit');
    Route::put('/sdgs/{sdg}', [SdgController::class, 'update'])
        ->middleware('permission:ods.manage')
        ->name('sdgs.update');

    Route::get('/ods-alignments', [OdsAlignmentController::class, 'index'])
        ->middleware('permission:ods.view')
        ->name('ods-alignments.index');
    Route::get('/ods-alignments/create', [OdsAlignmentController::class, 'create'])
        ->middleware('permission:ods.manage')
        ->name('ods-alignments.create');
    Route::post('/ods-alignments', [OdsAlignmentController::class, 'store'])
        ->middleware('permission:ods.manage')
        ->name('ods-alignments.store');
    Route::get('/ods-alignments/{odsAlignment}/edit', [OdsAlignmentController::class, 'edit'])
        ->middleware('permission:ods.manage')
        ->name('ods-alignments.edit');
    Route::put('/ods-alignments/{odsAlignment}', [OdsAlignmentController::class, 'update'])
        ->middleware('permission:ods.manage')
        ->name('ods-alignments.update');

    Route::get('/public-entities', [PublicEntityController::class, 'index'])
        ->middleware('permission:entities.view')
        ->name('public-entities.index');
    Route::get('/public-entities/create', [PublicEntityController::class, 'create'])
        ->middleware('permission:entities.manage')
        ->name('public-entities.create');
    Route::post('/public-entities', [PublicEntityController::class, 'store'])
        ->middleware('permission:entities.manage')
        ->name('public-entities.store');
    Route::get('/public-entities/{publicEntity}/edit', [PublicEntityController::class, 'edit'])
        ->middleware('permission:entities.manage')
        ->name('public-entities.edit');
    Route::put('/public-entities/{publicEntity}', [PublicEntityController::class, 'update'])
        ->middleware('permission:entities.manage')
        ->name('public-entities.update');

    Route::get('/goals', [InstitutionalGoalController::class, 'index'])
        ->middleware('permission:goals.view')
        ->name('goals.index');
    Route::get('/goals/create', [InstitutionalGoalController::class, 'create'])
        ->middleware('permission:goals.manage')
        ->name('goals.create');
    Route::post('/goals', [InstitutionalGoalController::class, 'store'])
        ->middleware('permission:goals.manage')
        ->name('goals.store');
    Route::get('/goals/{goal}/edit', [InstitutionalGoalController::class, 'edit'])
        ->middleware('permission:goals.manage')
        ->name('goals.edit');
    Route::put('/goals/{goal}', [InstitutionalGoalController::class, 'update'])
        ->middleware('permission:goals.manage')
        ->name('goals.update');

    Route::get('/indicators', [IndicatorController::class, 'index'])
        ->middleware('permission:goals.view')
        ->name('indicators.index');
    Route::get('/indicators/create', [IndicatorController::class, 'create'])
        ->middleware('permission:goals.manage')
        ->name('indicators.create');
    Route::post('/indicators', [IndicatorController::class, 'store'])
        ->middleware('permission:goals.manage')
        ->name('indicators.store');
    Route::get('/indicators/{indicator}/edit', [IndicatorController::class, 'edit'])
        ->middleware('permission:goals.manage')
        ->name('indicators.edit');
    Route::put('/indicators/{indicator}', [IndicatorController::class, 'update'])
        ->middleware('permission:goals.manage')
        ->name('indicators.update');

    Route::get('/investment-projects', [InvestmentProjectController::class, 'index'])
        ->middleware('permission:projects.view')
        ->name('investment-projects.index');
    Route::get('/investment-projects/create', [InvestmentProjectController::class, 'create'])
        ->middleware('permission:projects.manage')
        ->name('investment-projects.create');
    Route::post('/investment-projects', [InvestmentProjectController::class, 'store'])
        ->middleware('permission:projects.manage')
        ->name('investment-projects.store');
    Route::get('/investment-projects/{investmentProject}', [InvestmentProjectController::class, 'show'])
        ->middleware('permission:projects.view')
        ->name('investment-projects.show');
    Route::get('/investment-projects/{investmentProject}/edit', [InvestmentProjectController::class, 'edit'])
        ->middleware('permission:projects.manage')
        ->name('investment-projects.edit');
    Route::put('/investment-projects/{investmentProject}', [InvestmentProjectController::class, 'update'])
        ->middleware('permission:projects.manage')
        ->name('investment-projects.update');
    Route::post('/investment-projects/{investmentProject}/documents', [InvestmentProjectController::class, 'storeDocument'])
        ->middleware('permission:projects.manage')
        ->name('investment-projects.documents.store');
    Route::get('/investment-projects/{investmentProject}/documents/{document}', [InvestmentProjectController::class, 'downloadDocument'])
        ->middleware('permission:projects.view')
        ->name('investment-projects.documents.download');
    Route::delete('/investment-projects/{investmentProject}/documents/{document}', [InvestmentProjectController::class, 'destroyDocument'])
        ->middleware('permission:projects.manage')
        ->name('investment-projects.documents.destroy');

    Route::get('/audit-logs', [AuditLogController::class, 'index'])
        ->middleware('permission:audit.view')
        ->name('audit-logs.index');

    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware('permission:reports.view')
        ->name('reports.index');
    Route::get('/reports/{dataset}/{format}', [ReportController::class, 'export'])
        ->middleware('permission:reports.view')
        ->name('reports.export');
});

require __DIR__.'/auth.php';
