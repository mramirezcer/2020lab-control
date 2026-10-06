<?php

namespace Tests\Unit;

use App\Domain\Seguimiento\EstadosProyecto;
use App\Models\Actividad;
use App\Models\Auditoria;
use App\Models\CertificacionFase;
use App\Models\Evidencia;
use App\Models\Fase;
use App\Models\HistorialFechaCompromiso;
use App\Models\ObservacionDirector;
use App\Models\Proceso;
use App\Models\RespuestaObservacion;
use App\Models\RevisionDirector;
use App\Models\Rol;
use App\Models\Verificacion;
use PHPUnit\Framework\TestCase;

class P11AModeloDatosTest extends TestCase
{
    public function test_estados_maestros_son_los_acordados(): void
    {
        $this->assertSame([
            'POR_REALIZAR',
            'EN_DESARROLLO',
            'EN_VERIFICACION',
            'CONCLUIDO',
            'BLOQUEADO',
        ], EstadosProyecto::DESARROLLO);

        $this->assertSame([
            'NO_VERIFICADO',
            'PENDIENTE',
            'VERIFICADO',
            'CON_OBSERVACIONES',
        ], EstadosProyecto::VERIFICACION);

        $this->assertSame([
            'BAJA' => 1,
            'MEDIA' => 2,
            'ALTA' => 3,
            'CRITICA' => 5,
        ], EstadosProyecto::PRIORIDADES);
    }

    public function test_estados_del_director_son_los_acordados(): void
    {
        $this->assertContains('ABIERTA', EstadosProyecto::OBSERVACION_DIRECTOR);
        $this->assertContains('VERIFICADA', EstadosProyecto::OBSERVACION_DIRECTOR);
        $this->assertContains('NO_APLICA', EstadosProyecto::OBSERVACION_DIRECTOR);
        $this->assertContains('REVISADA_SIN_OBSERVACIONES', EstadosProyecto::REVISION_DIRECTOR);
        $this->assertContains('REVISADA_CON_OBSERVACIONES', EstadosProyecto::REVISION_DIRECTOR);
        $this->assertContains('REVISION_INFORMATIVA', EstadosProyecto::REVISION_DIRECTOR);
    }

    public function test_modelos_apuntan_a_tablas_y_llaves_correctas(): void
    {
        $contratos = [
            [new Rol(), 'roles', 'id_rol'],
            [new Fase(), 'fases', 'id_fase'],
            [new Proceso(), 'procesos', 'id_proceso'],
            [new Actividad(), 'actividades', 'id_actividad'],
            [new Evidencia(), 'evidencias', 'id_evidencia'],
            [new Verificacion(), 'verificaciones', 'id_verificacion'],
            [new ObservacionDirector(), 'observaciones_director', 'id_observacion'],
            [new RespuestaObservacion(), 'respuestas_observacion', 'id_respuesta_observacion'],
            [new RevisionDirector(), 'revisiones_director', 'id_revision'],
            [new HistorialFechaCompromiso(), 'historial_fechas_compromiso', 'id_historial_fecha'],
            [new CertificacionFase(), 'certificaciones_fase', 'id_certificacion'],
            [new Auditoria(), 'auditoria', 'id_auditoria'],
        ];

        foreach ($contratos as [$model, $table, $key]) {
            $this->assertSame($table, $model->getTable());
            $this->assertSame($key, $model->getKeyName());
        }
    }
}