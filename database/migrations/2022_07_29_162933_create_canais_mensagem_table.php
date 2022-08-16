<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCanaisMensagemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canais_mensagem', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_manifestacao')->unsigned();
            $table->foreign('id_manifestacao')->references('id')->on('manifests');
            $table->integer('id_status');
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
        Schema::dropIfExists('canais_mensagem');
    }
}
