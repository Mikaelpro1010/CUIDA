<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManifestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manifests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('protocolo', false, true)->nullable(false);
            $table->integer('id_app_user')->unsigned();
            $table->foreign('id_app_user')->references('id')->on('app_users');
            $table->integer('id_situacao');
            $table->integer('id_tipo_manifestacao');
            $table->integer('id_estado_processo');
            $table->integer('id_motivacao');
            $table->text('manifestacao');
            $table->text('contextualizacao')->nullable();
            $table->text('providencia_adotada')->nullable();
            $table->text('conclusao')->nullable();
            $table->dateTime('data_finalizacao')->nullable();
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
        Schema::dropIfExists('manifests');
    }
}
