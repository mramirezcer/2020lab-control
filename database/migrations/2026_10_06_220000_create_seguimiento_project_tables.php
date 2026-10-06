<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id_rol');
            $table->string('clave', 50)->unique();
            $table->string('nombre', 120);
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rol')->nullable()->after('id');
            $table->boolean('activo')->default(true)->after('password');
            $table->timestamp('ultimo_acceso_at')->nullable()->after('activo');

            $table->foreign('id_rol')
                ->references('id_rol')
                ->on('roles')
                ->nullOnDelete();

            $table->index(['id_rol', 'activo'], 'idx_users_rol_activo');
        });

        Schema::create('fases', function (Blueprint $table) {
            $table->bigIncrements('id_fase');
            $table->string('clave', 20)->unique();
            $table->string('nombre', 180);
            $table->text('descripcion')->nullable();
            $table->unsignedSmallInteger('orden');
            $table->unsignedBigInteger('id_responsable')->nullable();
            $table->unsignedBigInteger('id_verificador')->nullable();
            $table->string('estado_desarrollo', 30)->default('POR_REALIZAR');
            $table->string('estado_verificacion', 30)->default('NO_VERIFICADO');
            $table->string('prioridad', 20)->default('MEDIA');
            $table->date('fecha_inicio_planificada')->nullable();
            $table->date('fecha_entrega_comprometida')->nullable();
            $table->timestamp('fecha_inicio_real')->nullable();
            $table->timestamp('fecha_fin_real')->nullable();
            $table->decimal('porcentaje_avance', 5, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_responsable')->references('id')->on('users')->nullOnDelete();
            $table->foreign('id_verificador')->references('id')->on('users')->nullOnDelete();
            $table->index(['orden', 'estado_desarrollo'], 'idx_fases_orden_estado');
        });

        Schema::create('procesos', function (Blueprint $table) {
            $table->bigIncrements('id_proceso');
            $table->unsignedBigInteger('id_fase');
            $table->string('clave', 30);
            $table->string('nombre', 180);
            $table->text('descripcion')->nullable();
            $table->unsignedSmallInteger('orden');
            $table->unsignedBigInteger('id_responsable')->nullable();
            $table->unsignedBigInteger('id_verificador')->nullable();
            $table->string('estado_desarrollo', 30)->default('POR_REALIZAR');
            $table->string('estado_verificacion', 30)->default('NO_VERIFICADO');
            $table->string('prioridad', 20)->default('MEDIA');
            $table->date('fecha_inicio_planificada')->nullable();
            $table->date('fecha_entrega_comprometida')->nullable();
            $table->timestamp('fecha_inicio_real')->nullable();
            $table->timestamp('fecha_fin_real')->nullable();
            $table->decimal('porcentaje_avance', 5, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_fase')->references('id_fase')->on('fases')->cascadeOnDelete();
            $table->foreign('id_responsable')->references('id')->on('users')->nullOnDelete();
            $table->foreign('id_verificador')->references('id')->on('users')->nullOnDelete();
            $table->unique(['id_fase', 'clave'], 'uq_procesos_fase_clave');
            $table->index(['id_fase', 'orden'], 'idx_procesos_fase_orden');
        });

        Schema::create('actividades', function (Blueprint $table) {
            $table->bigIncrements('id_actividad');
            $table->unsignedBigInteger('id_proceso');
            $table->string('clave', 40);
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->unsignedSmallInteger('orden');
            $table->unsignedBigInteger('id_responsable')->nullable();
            $table->unsignedBigInteger('id_verificador')->nullable();
            $table->string('estado_desarrollo', 30)->default('POR_REALIZAR');
            $table->string('estado_verificacion', 30)->default('NO_VERIFICADO');
            $table->string('prioridad', 20)->default('MEDIA');
            $table->unsignedTinyInteger('peso')->default(2);
            $table->date('fecha_inicio_planificada')->nullable();
            $table->date('fecha_entrega_comprometida')->nullable();
            $table->timestamp('fecha_inicio_real')->nullable();
            $table->timestamp('fecha_fin_real')->nullable();
            $table->decimal('porcentaje_avance', 5, 2)->default(0);
            $table->text('motivo_bloqueo')->nullable();
            $table->timestamps();

            $table->foreign('id_proceso')->references('id_proceso')->on('procesos')->cascadeOnDelete();
            $table->foreign('id_responsable')->references('id')->on('users')->nullOnDelete();
            $table->foreign('id_verificador')->references('id')->on('users')->nullOnDelete();
            $table->unique(['id_proceso', 'clave'], 'uq_actividades_proceso_clave');
            $table->index(['id_proceso', 'orden'], 'idx_actividades_proceso_orden');
        });

        Schema::create('evidencias', function (Blueprint $table) {
            $table->bigIncrements('id_evidencia');
            $table->string('tipo_entidad', 30);
            $table->unsignedBigInteger('id_entidad');
            $table->string('tipo_evidencia', 50)->default('GENERAL');
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->string('referencia', 2048)->nullable();
            $table->char('hash_sha256', 64)->nullable();
            $table->unsignedBigInteger('id_usuario_registra');
            $table->timestamp('registrado_at')->useCurrent();
            $table->timestamps();

            $table->foreign('id_usuario_registra')->references('id')->on('users')->restrictOnDelete();
            $table->index(['tipo_entidad', 'id_entidad'], 'idx_evidencias_entidad');
        });

        Schema::create('verificaciones', function (Blueprint $table) {
            $table->bigIncrements('id_verificacion');
            $table->string('tipo_entidad', 30);
            $table->unsignedBigInteger('id_entidad');
            $table->string('estado_verificacion', 30);
            $table->text('comentario')->nullable();
            $table->unsignedBigInteger('id_usuario_verifica');
            $table->timestamp('verificado_at')->useCurrent();
            $table->timestamps();

            $table->foreign('id_usuario_verifica')->references('id')->on('users')->restrictOnDelete();
            $table->index(['tipo_entidad', 'id_entidad'], 'idx_verificaciones_entidad');
        });

        Schema::create('observaciones_director', function (Blueprint $table) {
            $table->bigIncrements('id_observacion');
            $table->string('tipo_entidad', 30);
            $table->unsignedBigInteger('id_entidad');
            $table->string('asunto', 200);
            $table->text('observacion');
            $table->string('estado_observacion', 30)->default('ABIERTA');
            $table->unsignedBigInteger('id_director');
            $table->timestamp('abierta_at')->useCurrent();
            $table->timestamp('cerrada_at')->nullable();
            $table->timestamps();

            $table->foreign('id_director')->references('id')->on('users')->restrictOnDelete();
            $table->index(['tipo_entidad', 'id_entidad'], 'idx_observaciones_entidad');
            $table->index('estado_observacion', 'idx_observaciones_estado');
        });

        Schema::create('respuestas_observacion', function (Blueprint $table) {
            $table->bigIncrements('id_respuesta_observacion');
            $table->unsignedBigInteger('id_observacion');
            $table->unsignedBigInteger('id_usuario');
            $table->text('respuesta');
            $table->timestamps();

            $table->foreign('id_observacion')
                ->references('id_observacion')
                ->on('observaciones_director')
                ->cascadeOnDelete();

            $table->foreign('id_usuario')->references('id')->on('users')->restrictOnDelete();
            $table->index(['id_observacion', 'created_at'], 'idx_respuestas_observacion_fecha');
        });

        Schema::create('revisiones_director', function (Blueprint $table) {
            $table->bigIncrements('id_revision');
            $table->string('tipo_entidad', 30);
            $table->unsignedBigInteger('id_entidad');
            $table->string('estado_revision', 40);
            $table->text('comentario')->nullable();
            $table->unsignedBigInteger('id_director');
            $table->timestamp('revisado_at')->useCurrent();
            $table->timestamps();

            $table->foreign('id_director')->references('id')->on('users')->restrictOnDelete();
            $table->index(['tipo_entidad', 'id_entidad'], 'idx_revisiones_entidad');
        });

        Schema::create('historial_fechas_compromiso', function (Blueprint $table) {
            $table->bigIncrements('id_historial_fecha');
            $table->string('tipo_entidad', 30);
            $table->unsignedBigInteger('id_entidad');
            $table->date('fecha_anterior')->nullable();
            $table->date('fecha_nueva');
            $table->text('justificacion');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_usuario')->references('id')->on('users')->restrictOnDelete();
            $table->index(['tipo_entidad', 'id_entidad'], 'idx_historial_fechas_entidad');
        });

        Schema::create('certificaciones_fase', function (Blueprint $table) {
            $table->bigIncrements('id_certificacion');
            $table->unsignedBigInteger('id_fase');
            $table->string('resultado', 30);
            $table->text('comentario')->nullable();
            $table->unsignedBigInteger('id_usuario_certifica');
            $table->timestamp('certificado_at')->useCurrent();
            $table->timestamps();

            $table->foreign('id_fase')->references('id_fase')->on('fases')->cascadeOnDelete();
            $table->foreign('id_usuario_certifica')->references('id')->on('users')->restrictOnDelete();
            $table->index(['id_fase', 'certificado_at'], 'idx_certificaciones_fase_fecha');
        });

        Schema::create('auditoria', function (Blueprint $table) {
            $table->bigIncrements('id_auditoria');
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->string('accion', 100);
            $table->string('tipo_entidad', 50);
            $table->unsignedBigInteger('id_entidad')->nullable();
            $table->json('valores_anteriores')->nullable();
            $table->json('valores_nuevos')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 1024)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_usuario')->references('id')->on('users')->nullOnDelete();
            $table->index(['tipo_entidad', 'id_entidad'], 'idx_auditoria_entidad');
            $table->index(['id_usuario', 'created_at'], 'idx_auditoria_usuario_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria');
        Schema::dropIfExists('certificaciones_fase');
        Schema::dropIfExists('historial_fechas_compromiso');
        Schema::dropIfExists('revisiones_director');
        Schema::dropIfExists('respuestas_observacion');
        Schema::dropIfExists('observaciones_director');
        Schema::dropIfExists('verificaciones');
        Schema::dropIfExists('evidencias');
        Schema::dropIfExists('actividades');
        Schema::dropIfExists('procesos');
        Schema::dropIfExists('fases');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_rol']);
            $table->dropIndex('idx_users_rol_activo');
            $table->dropColumn(['id_rol', 'activo', 'ultimo_acceso_at']);
        });

        Schema::dropIfExists('roles');
    }
};