<?php

namespace Tests\Feature;

use App\Models\InvestmentProject;
use App\Models\Role;
use App\Models\StrategicPlan;
use App\Models\User;
use Database\Seeders\SipeipAccessSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacScreenAccessTest extends TestCase
{
    use RefreshDatabase;

    // Prepara roles y permisos antes de cada prueba.
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(SipeipAccessSeeder::class);

        // Reserva el ID 1 para no activar el bypass de superadministrador en estas pruebas.
        User::factory()->create();
    }

    public function test_tecnico_snp_no_puede_administrar_roles(): void
    {
        $user = $this->userWithRole('Tecnico de Planificacion SNP');

        // El tecnico registra planificacion, pero no administra seguridad.
        $this->actingAs($user)->get(route('roles.create'))->assertForbidden();
        $this->actingAs($user)->get(route('strategic-plans.create'))->assertOk();
    }

    public function test_revisor_no_crea_planes_pero_si_cambia_estado(): void
    {
        $user = $this->userWithRole('Revisor Institucional SNP');
        $plan = StrategicPlan::create([
            'code' => 'RBAC-PLAN-01',
            'name' => 'Plan de prueba RBAC',
            'institution' => 'SNP',
            'period_start' => 2026,
            'period_end' => 2029,
            'status' => 'review',
        ]);

        // El revisor no crea planes; su funcion es validar o devolver.
        $this->actingAs($user)->get(route('strategic-plans.create'))->assertForbidden();

        $this->actingAs($user)
            ->patch(route('strategic-plans.status', $plan), [
                'status' => 'approved',
                'observations' => 'Validado por revisor.',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('approved', $plan->refresh()->status);
    }

    public function test_autoridad_validante_puede_aprobar_sin_editar_catalogos(): void
    {
        $user = $this->userWithRole('Autoridad Validante');
        $plan = StrategicPlan::create([
            'code' => 'RBAC-PLAN-02',
            'name' => 'Plan para autoridad',
            'institution' => 'SNP',
            'period_start' => 2026,
            'period_end' => 2029,
            'status' => 'review',
        ]);

        // La autoridad aprueba oficialmente, pero no configura roles.
        $this->actingAs($user)->get(route('roles.index'))->assertForbidden();

        $this->actingAs($user)
            ->patch(route('strategic-plans.status', $plan), [
                'status' => 'approved',
                'observations' => 'Aprobado por autoridad.',
            ])
            ->assertSessionHasNoErrors();
    }

    public function test_auditor_solo_consulta_y_no_modifica_proyectos(): void
    {
        $user = $this->userWithRole('Auditor/Control Interno');

        InvestmentProject::create([
            'code' => 'RBAC-PROY-01',
            'name' => 'Proyecto de prueba',
            'budget' => 1000,
            'status' => 'review',
        ]);

        // Auditoria solo consulta informacion y trazas, sin modificar proyectos.
        $this->actingAs($user)->get(route('investment-projects.index'))->assertOk();
        $this->actingAs($user)->get(route('investment-projects.create'))->assertForbidden();
        $this->actingAs($user)->get(route('audit-logs.index'))->assertOk();
    }

    public function test_administrador_tiene_control_total(): void
    {
        $user = $this->userWithRole('Administrador del Sistema');

        // El administrador tiene acceso completo a configuracion y catalogos.
        $this->actingAs($user)->get(route('roles.create'))->assertOk();
        $this->actingAs($user)->get(route('users.create'))->assertOk();
        $this->actingAs($user)->get(route('public-entities.create'))->assertOk();
    }

    // Crea un usuario activo asociado al rol solicitado.
    private function userWithRole(string $roleName): User
    {
        return User::factory()->create([
            'role_id' => Role::where('name', $roleName)->firstOrFail()->id,
            'status' => 'active',
        ]);
    }
}
