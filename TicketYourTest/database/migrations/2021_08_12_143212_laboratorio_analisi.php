<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LaboratorioAnalisi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorio_analisi', function (Blueprint $table) {
            $table->id();
            $table->string('partita_iva')->unique();
            $table->string('nome');
            $table->string('citta');
            $table->string('provincia');
            $table->string('indirizzo'); // da vedere meglio
            $table->string('email')->unique();
            $table->string('password');
            $table->double('coordinata_x');
            $table->double('coordinata_y');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laboratorio_analisi');
    }
}