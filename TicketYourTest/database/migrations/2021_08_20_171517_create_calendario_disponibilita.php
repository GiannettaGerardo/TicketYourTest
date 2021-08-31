<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarioDisponibilita extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendario_disponibilita', function (Blueprint $table) {
            $table->string('giorno_settimana');
            $table->unsignedBigInteger('id_laboratorio');
            $table->time('oraApertura');
            $table->time('oraChiusura');

            $table->primary(['giorno_settimana', 'id_laboratorio']);
            $table->foreign('id_laboratorio')->references('id')->on('laboratorio_analisi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendario_disponibilita');
    }
}
