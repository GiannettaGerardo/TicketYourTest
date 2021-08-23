<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTamponiProposti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tamponi_proposti', function (Blueprint $table) {
            $table->string('partita_iva_lab');
            $table->unsignedBigInteger('id_tampone');
            $table->double('costo',4, 2);

            $table->foreign('partita_iva_lab')->references('partita_iva')->on('laboratorio_analisi')->onDelete('cascade');
            $table->foreign('id_tampone')->references('id')->on('tamponi')->onDelete('cascade');

            $table->primary(['partita_iva_lab', 'id_tampone']);
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
        Schema::dropIfExists('tamponi_proposti');
    }
}
