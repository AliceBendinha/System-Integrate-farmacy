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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('admin'); 
            $table->timestamps();
        });


       Schema::create('farmacias', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('localizacao')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });


        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->timestamps();
        });

        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo')->unique();
            $table->decimal('preco', 10, 2);
            $table->date('data_validade')->nullable();
            $table->foreignId('categoria_id')->constrained()->onDelete('restrict');
            $table->timestamps();
        });

        Schema::create('estoques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmacia_id')->constrained()->onDelete('cascade');
            $table->foreignId('produto_id')->constrained()->onDelete('cascade');
            $table->integer('quantidade');
            $table->integer('stock_minimo')->default(5);
            $table->timestamps();

            $table->unique(['farmacia_id', 'produto_id']);
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('farmacias');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('produtos');
        Schema::dropIfExists('estoques');
    }
};
