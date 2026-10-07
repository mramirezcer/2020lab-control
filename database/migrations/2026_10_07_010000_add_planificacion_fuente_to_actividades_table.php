<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actividades', function (Blueprint $table): void {
            $table->string('responsable_recomendado', 10)
                ->nullable()
                ->after('orden');

            $table->string('revision_apoyo', 10)
                ->nullable()
                ->after('responsable_recomendado');

            $table->index(
                ['responsable_recomendado', 'revision_apoyo'],
                'idx_actividades_asignacion_fuente'
            );
        });
    }

    public function down(): void
    {
        Schema::table('actividades', function (Blueprint $table): void {
            $table->dropIndex('idx_actividades_asignacion_fuente');
            $table->dropColumn([
                'responsable_recomendado',
                'revision_apoyo',
            ]);
        });
    }
};