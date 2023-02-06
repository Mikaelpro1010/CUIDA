<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnidadesSecrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('secretaria_id')->unsigned();
            $table->foreign('secretaria_id')->references('id')->on('secretarias');
            $table->string('nome');
            $table->string('nome_oficial')->nullable();
            $table->text('descricao')->nullable();
            $table->string('token');
            $table->float('nota')->nullable();
            $table->boolean('ativo');
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
        Schema::dropIfExists('unidades_secr');
    }
}
