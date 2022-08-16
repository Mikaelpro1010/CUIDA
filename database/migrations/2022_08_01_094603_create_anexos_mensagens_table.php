<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnexosMensagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anexos_mensagens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_mensagem')->unsigned();
            $table->foreign('id_mensagem')->references('id')->on('mensagens');
            $table->string('nome');
            $table->string('nome_original');
            $table->string('caminho');
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
        Schema::dropIfExists('anexos_mensagens');
    }
}
