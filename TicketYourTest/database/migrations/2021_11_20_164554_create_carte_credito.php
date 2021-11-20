<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarteCredito extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carte_credito', function (Blueprint $table) {
            $table->string('numero', '16')->primary();
            $table->date('exp');
            $table->smallInteger('cvv');
            $table->string('cf_possessore', '16');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carte_credito');
    }
}
