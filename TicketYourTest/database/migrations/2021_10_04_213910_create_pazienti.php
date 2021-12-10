<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePazienti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pazienti', function (Blueprint $table) {
            $table->unsignedBigInteger('id_prenotazione');
            $table->string('codice_fiscale');
            $table->string('nome')->nullable();
            $table->string('cognome')->nullable();
            $table->string('email')->nullable();
            $table->string('citta_residenza')->nullable();
            $table->string('provincia_residenza')->nullable();
            $table->boolean('risultato_comunicato_ad_asl_da_medico')->default(0);

            $table->primary(['id_prenotazione', 'codice_fiscale']);
            $table->foreign('id_prenotazione')->references('id')->on('prenotazioni')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pazienti');
    }
}
