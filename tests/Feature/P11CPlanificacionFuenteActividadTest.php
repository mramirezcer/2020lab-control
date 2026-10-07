<?php

namespace Tests\Feature;

use App\Models\Actividad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class P11CPlanificacionFuenteActividadTest extends TestCase
{
    use RefreshDatabase;

    public function test_actividad_separa_planificacion_fuente_de_usuarios_reales(): void
    {
        $this->assertTrue(Schema::hasColumns('actividades', [
            'responsable_recomendado',
            'revision_apoyo',
            'id_responsable',
            'id_verificador',
        ]));
    }

    public function test_campos_fuente_admiten_a_b_y_asignacion_conjunta(): void
    {
        $campos = [
            ['A', 'B'],
            ['B', 'A'],
            ['A+B', 'A+B'],
        ];

        foreach ($campos as [$responsable, $revision]) {
            $actividad = new Actividad();
            $actividad->responsable_recomendado = $responsable;
            $actividad->revision_apoyo = $revision;

            $this->assertSame($responsable, $actividad->responsable_recomendado);
            $this->assertSame($revision, $actividad->revision_apoyo);
        }
    }
}