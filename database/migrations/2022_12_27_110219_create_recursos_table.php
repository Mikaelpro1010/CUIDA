<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recursos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('manifestacoes_id')->unsigned();
            $table->foreign('manifestacoes_id')->references('id')->on('manifestacoes');
            $table->text('recurso');
            $table->text('resposta')->nullable();
            $table->integer('autor_resposta')->unsigned()->nullable();
            $table->foreign('autor_resposta')->references('id')->on('users');
            $table->dateTime('data_resposta')->nullable();
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
        Schema::dropIfExists('recursos');
    }
}
