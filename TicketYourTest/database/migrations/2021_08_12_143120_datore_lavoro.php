<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DatoreLavoro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datore_lavoro', function (Blueprint $table) {
            $table->string('codice_fiscale')->primary();
            $table->string('partita_iva')->unique();
            $table->string('nome_azienda');
            $table->string('citta_sede_aziendale');
            $table->string('provincia_sede_aziendale');

            $table->foreign('codice_fiscale')->references('codice_fiscale')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datore_lavoro');
    }
}
