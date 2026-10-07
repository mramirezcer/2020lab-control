<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class P11CCatalogoMaestroProcesosTest extends TestCase
{
    private function catalogo(): array
    {
        $ruta = dirname(__DIR__, 2).'/database/data/plan_maestro_2020lab_v2.json';
        $contenido = file_get_contents($ruta);

        $this->assertNotFalse($contenido);

        return json_decode($contenido, true, 512, JSON_THROW_ON_ERROR);
    }

    public function test_cardinalidad_maestra(): void
    {
        $catalogo = $this->catalogo();
        $this->assertCount(16, $catalogo['phases']);

        $procesos = 0;
        $actividades = [];

        foreach ($catalogo['phases'] as $fase) {
            $procesos += count($fase['processes']);

            foreach ($fase['processes'] as $proceso) {
                foreach ($proceso['activity_ids'] as $id) {
                    $actividades[] = $id;
                }
            }
        }

        $this->assertSame(81, $procesos);
        $this->assertCount(200, $actividades);
        $this->assertCount(200, array_unique($actividades));
    }

    public function test_claves_de_proceso_y_actividad_corresponden_a_su_fase(): void
    {
        $catalogo = $this->catalogo();

        foreach ($catalogo['phases'] as $fase) {
            foreach ($fase['processes'] as $proceso) {
                $this->assertMatchesRegularExpression(
                    '/^'.preg_quote($fase['key'], '/').'-P\d{2}$/',
                    $proceso['key']
                );

                $this->assertNotEmpty($proceso['activity_ids']);

                foreach ($proceso['activity_ids'] as $id) {
                    $this->assertMatchesRegularExpression(
                        '/^'.preg_quote($fase['key'], '/').'-\d{2}$/',
                        $id
                    );
                }
            }
        }
    }

    public function test_conteos_por_fase_coinciden_con_plan_maestro(): void
    {
        $esperados = [
            'F0'=>27,'F1'=>20,'F2'=>28,'F3'=>6,'F4'=>9,'F5'=>7,'F6'=>11,'F7'=>7,
            'F8'=>14,'F9'=>8,'F10'=>8,'F11'=>9,'F12'=>13,'F13'=>10,'F14'=>11,'F15'=>12,
        ];

        foreach ($this->catalogo()['phases'] as $fase) {
            $total = array_sum(array_map(
                fn (array $proceso): int => count($proceso['activity_ids']),
                $fase['processes']
            ));

            $this->assertSame($esperados[$fase['key']], $total, $fase['key']);
        }
    }

    public function test_modulos_adicionales_y_f15_final_se_preservan(): void
    {
        $fases = $this->catalogo()['phases'];

        $this->assertSame('F12', $fases[12]['key']);
        $this->assertStringContainsString('Módulo adicional', $fases[12]['name']);

        $this->assertSame('F15', $fases[15]['key']);
        $this->assertStringContainsString('Módulo adicional', $fases[15]['name']);
    }

    public function test_agrupaciones_criticas_quedan_fijadas(): void
    {
        $fases = $this->catalogo()['phases'];

        $this->assertSame('Gobierno técnico y plataforma de desarrollo', $fases[0]['processes'][0]['name']);
        $this->assertSame('Interfaces de analizadores', $fases[6]['processes'][2]['name']);
        $this->assertSame('Intercambio operativo e idempotencia', $fases[13]['processes'][2]['name']);
        $this->assertSame('Independencia del módulo adicional', $fases[15]['processes'][5]['name']);
    }
}
