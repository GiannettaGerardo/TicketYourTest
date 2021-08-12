<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CittadinoPrivato extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cittadino_privato', function (Blueprint $table) {
            $table->string('codice_fiscale')->primary();

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
        Schema::dropIfExists('cittadino_privato');
    }
}
