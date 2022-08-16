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
            $table->integer('id_manifestacao')->unsigned();
            $table->foreign('id_manifestacao')->references('id')->on('manifests');
            $table->text('recurso');
            $table->text('resposta')->nullable();
            $table->integer('id_respondido_por')->unsigned()->nullable();
            $table->foreign('id_respondido_por')->references('id')->on('manifests');
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
