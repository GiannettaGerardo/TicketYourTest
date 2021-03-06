<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Transazioni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transazioni', function (Blueprint $table) {
            $table->id();
            $table->double('importo');
            $table->unsignedBigInteger('id_prenotazione');
            $table->unsignedBigInteger('id_laboratorio');
            $table->boolean('pagamento_online')->default(0);
            $table->boolean('pagamento_effettuato')->default(0);

            $table->foreign('id_prenotazione')
                ->references('id')->on('prenotazioni')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('id_laboratorio')
                ->references('id')->on('laboratorio_analisi')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transazioni');
    }
}
