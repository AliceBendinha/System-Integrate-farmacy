<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesquisas', function (Blueprint $table) {
        $table->id();

        $table->foreignId('usuario_id')
          ->nullable()
          ->constrained('users')
          ->nullOnDelete();

        $table->string('termo');
        $table->integer('resultados_encontrados')->default(0);

        $table->enum('tipo_pesquisa', ['produto', 'farmacia', 'servico']);

        $table->timestamps();

        $table->index(['termo', 'tipo_pesquisa']);
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
