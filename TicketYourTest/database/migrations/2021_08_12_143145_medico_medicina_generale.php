<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MedicoMedicinaGenerale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medico_medicina_generale', function (Blueprint $table) {
            $table->string('codice_fiscale')->primary();
            $table->string('partita_iva')->unique();

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
        Schema::dropIfExists('medico_medicina_generale');
    }
}
