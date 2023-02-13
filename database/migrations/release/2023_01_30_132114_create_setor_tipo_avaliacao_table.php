<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetorTipoAvaliacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setor_tipo_avaliacao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('setor_id')->unsigned();
            $table->foreign('setor_id')->references('id')->on('setores');
            $table->integer('tipo_avaliacao_id')->unsigned();
            $table->foreign('tipo_avaliacao_id')->references('id')->on('tipo_avaliacoes');
            $table->float('nota');
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
        Schema::dropIfExists('setor_tipo_avaliacao');
    }
}
