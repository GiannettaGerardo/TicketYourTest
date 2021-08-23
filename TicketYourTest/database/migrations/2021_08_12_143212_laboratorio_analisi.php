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
            $table->double('coordinata_x')->nullable();
            $table->double('coordinata_y')->nullable();
            $table->boolean('convenzionato')->default(0);
            $table->boolean('calendario_compilato')->default(0);
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
