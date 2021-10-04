<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrenotazioni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prenotazioni', function (Blueprint $table) {
            $table->id();
            $table->timestamp('data_prenotazione');
            $table->timestamp('data_tampone')->nullable(true);
            $table->unsignedBigInteger('id_tampone');
            $table->string('cf_prenotante');
            $table->string('email');
            $table->string('numero_cellulare', 10);
            $table->unsignedBigInteger('id_laboratorio');

            $table->foreign('id_tampone')->references('id')->on('tamponi');
            $table->foreign('cf_prenotante')->references('codice_fiscale')->on('users');
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
        Schema::dropIfExists('prenotazioni');
    }
}
