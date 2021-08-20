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
            $table->unsignedBigInteger('id_laboratorio');
            $table->unsignedBigInteger('id_tampone');
            $table->double('costo',4, 2);

            $table->foreign('id_laboratorio')->references('id')->on('laboratorio_analisi');
            $table->foreign('id_tampone')->references('id')->on('tamponi');

            $table->primary(['id_laboratorio', 'id_tampone']);
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
