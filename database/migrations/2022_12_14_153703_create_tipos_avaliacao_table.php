<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiposAvaliacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_avaliacao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('secretaria_id')->unsigned();
            $table->foreign('secretaria_id')->references('id')->on('secretarias');
            $table->string('nome');
            $table->text('pergunta')->nullable();
            $table->boolean('obrigatorio')->nullable();
            $table->boolean('default')->nullable();
            $table->boolean('ativo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_avaliacao');
    }
}
