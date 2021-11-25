<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referti', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_prenotazione');
            $table->string('cf_paziente', 16);
            $table->enum('esito_tampone', ['positivo', 'negativo', 'indeterminato'])->nullable();
            $table->float('quantita')->nullable();

            $table->foreign(['id_prenotazione', 'cf_paziente'])->references(['id_prenotazione', 'codice_fiscale'])->on('pazienti')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referti');
    }
}
