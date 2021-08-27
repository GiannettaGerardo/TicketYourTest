<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListaDipendenti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lista_dipendenti', function (Blueprint $table) {
            $table->char('partita_iva_datore', 11);
            $table->char('codice_fiscale', 16);
            $table->string('nome')->nullable();
            $table->string('cognome')->nullable();
            $table->string('email')->nullable();
            $table->string('citta_residenza')->nullable();
            $table->string('provincia_residenza')->nullable();
            $table->boolean('accettato')->default(0);

            $table->foreign('partita_iva_datore')->references('partita_iva')->on('datore_lavoro')->onDelete('cascade')->onUpdate('cascade');
            //$table->foreign('codice_fiscale')->references('codice_fiscale')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->primary(['partita_iva_datore', 'codice_fiscale']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lista_dipendenti');
    }
}
