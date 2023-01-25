<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoAvaliacaoUnidadeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_avaliacao_unidade', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tipo_avaliacao_id')->unsigned();
            $table->foreign('tipo_avaliacao_id')->references('id')->on('tipos_avaliacao');
            $table->integer('unidade_id')->unsigned();
            $table->foreign('unidade_id')->references('id')->on('unidades_secr');
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
        Schema::dropIfExists('tipo_avaliacao_unidade');
    }
}
