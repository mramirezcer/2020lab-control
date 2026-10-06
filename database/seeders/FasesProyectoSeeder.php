<?php

namespace Database\Seeders;

use App\Models\Fase;
use Illuminate\Database\Seeder;

class FasesProyectoSeeder extends Seeder
{
    /**
     * Carga idempotente del catalogo maestro de fases de 2020Lab V2.
     *
     * Solo mantiene metadatos maestros (clave, nombre, descripcion y orden).
     * Si una fase ya existe, NO reinicia estado, fechas, responsables,
     * verificadores, prioridad ni porcentaje de avance.
     */
    public function run(): void
    {
        $fases = [
            [
                'clave' => 'F0',
                'nombre' => 'Fundaciones, Arquitectura y Modelo de Datos',
                'descripcion' => 'Fundaciones tecnicas, arquitectura, identidad del laboratorio, modelo de datos, seguridad base, entornos y convenciones maestras.',
                'orden' => 0,
            ],
            [
                'clave' => 'F1',
                'nombre' => 'Alta de laboratorio',
                'descripcion' => 'Registro y alta de cada laboratorio como identidad tecnica independiente, con su contexto de acceso y configuracion inicial de cuenta.',
                'orden' => 1,
            ],
            [
                'clave' => 'F2',
                'nombre' => 'Configuracion inicial y Mantenimiento',
                'descripcion' => 'Configuracion inicial del laboratorio, catalogos base, parametros operativos y funciones de mantenimiento necesarias para la operacion.',
                'orden' => 2,
            ],
            [
                'clave' => 'F3',
                'nombre' => 'Paciente y recepcion',
                'descripcion' => 'Identificacion del paciente, expediente longitudinal, captura y recepcion operativa de solicitudes y datos asociados.',
                'orden' => 3,
            ],
            [
                'clave' => 'F4',
                'nombre' => 'Solicitud y operacion comercial',
                'descripcion' => 'Solicitud de estudios, precios, convenios, operacion comercial, caja y cobro operativo. La facturacion fiscal se mantiene separada.',
                'orden' => 4,
            ],
            [
                'clave' => 'F5',
                'nombre' => 'Preanalitica',
                'descripcion' => 'Preparacion preanalitica, identificacion, toma, recepcion, trazabilidad y manejo de muestras antes del procesamiento analitico.',
                'orden' => 5,
            ],
            [
                'clave' => 'F6',
                'nombre' => 'Analitica y validacion',
                'descripcion' => 'Procesamiento analitico, captura o recepcion de resultados, reglas, referencias y validacion tecnica y clinica.',
                'orden' => 6,
            ],
            [
                'clave' => 'F7',
                'nombre' => 'Resultados y cierre clinico',
                'descripcion' => 'Liberacion, cierre clinico, resguardo de resultados finales vigentes y mecanismos de consulta del resultado.',
                'orden' => 7,
            ],
            [
                'clave' => 'F8',
                'nombre' => 'Postanalitica, Reportes y Explotacion de Informacion',
                'descripcion' => 'Procesos postanaliticos, reportes operativos y explotacion estructurada de la informacion generada por el laboratorio.',
                'orden' => 8,
            ],
            [
                'clave' => 'F9',
                'nombre' => 'Informacion gerencial, Estadisticas y KPI',
                'descripcion' => 'Indicadores gerenciales, estadisticas, KPI y vistas ejecutivas para seguimiento de desempeno y operacion.',
                'orden' => 9,
            ],
            [
                'clave' => 'F10',
                'nombre' => 'Portales y comunicaciones',
                'descripcion' => 'Portales externos e internos y mecanismos de comunicacion para pacientes, medicos, empresas y otros actores autorizados.',
                'orden' => 10,
            ],
            [
                'clave' => 'F11',
                'nombre' => 'Relacion comercial APS',
                'descripcion' => 'Relacion comercial APS, reglas, convenios y procesos asociados que preceden a los servicios adicionales y a la interoperabilidad.',
                'orden' => 11,
            ],
            [
                'clave' => 'F12',
                'nombre' => 'Facturacion del laboratorio - Modulo adicional',
                'descripcion' => 'Modulo adicional contratado para facturacion fiscal del laboratorio a convenios y pacientes. No forma parte obligatoria del nucleo.',
                'orden' => 12,
            ],
            [
                'clave' => 'F13',
                'nombre' => 'Interoperabilidad',
                'descripcion' => 'Intercambio seguro con laboratorios de referencia, sistemas externos y otros actores mediante integraciones controladas, sin acceso directo entre tenants.',
                'orden' => 13,
            ],
            [
                'clave' => 'F14',
                'nombre' => 'Migracion y ciclo de vida',
                'descripcion' => 'Migracion desde versiones previas, respaldo exportable, continuidad, mantenimiento y ciclo de vida operativo de la plataforma.',
                'orden' => 14,
            ],
            [
                'clave' => 'F15',
                'nombre' => 'Calidad e Inventario - Modulo adicional',
                'descripcion' => 'Modulo adicional contratado para calidad e inventario. Debe permanecer como la ultima fase del orden maestro.',
                'orden' => 15,
            ],
        ];

        foreach ($fases as $datos) {
            $fase = Fase::query()->firstOrNew([
                'clave' => $datos['clave'],
            ]);

            $esNueva = ! $fase->exists;

            $fase->nombre = $datos['nombre'];
            $fase->descripcion = $datos['descripcion'];
            $fase->orden = $datos['orden'];

            if ($esNueva) {
                $fase->estado_desarrollo = 'POR_REALIZAR';
                $fase->estado_verificacion = 'NO_VERIFICADO';
                $fase->prioridad = 'MEDIA';
                $fase->porcentaje_avance = 0;
            }

            $fase->save();
        }
    }
}