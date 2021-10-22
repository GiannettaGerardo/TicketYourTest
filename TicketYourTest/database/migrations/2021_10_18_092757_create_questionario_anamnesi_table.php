<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionarioAnamnesiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionario_anamnesi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_prenotazione');
            $table->string('cf_paziente');
            $table->string('token', 36)->unique();
            $table->boolean('token_scaduto')->default(0);
            // Risposte alle domande
            $table->enum('motivazione', ['sintomi', 'contatto', 'controllo', 'accesso-struttura-sanitaria', 'viaggi-trasferta', 'lavoro', 'sport', 'scuola'])->nullable();
            $table->boolean('lavoro')->default(0);
            $table->boolean('contatto')->default(0);
            $table->boolean('quindici-giorni-dopo-contatto')->default(0);
            $table->boolean('tampone-fatto')->default(0);
            $table->boolean('isolamento')->default(0);
            $table->boolean('contagiato')->default(0);
            // Sintomi
            $table->boolean('febbre')->default(0);
            $table->boolean('tosse')->default(0);
            $table->boolean('difficolta-respiratorie')->default(0);
            $table->boolean('raffreddore')->default(0);
            $table->boolean('mal-di-gola')->default(0);
            $table->boolean('mancanza-gusto')->default(0);
            $table->boolean('dolori-muscolari')->default(0);
            $table->boolean('cefalea')->default(0);

            $table->primary(['id_prenotazione', 'cf_paziente']);
            $table->foreign(['id_prenotazione', 'cf_paziente'])->references(['id_prenotazione', 'codice_fiscale'])->on('pazienti')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questionario_anamnesi');
    }
}
