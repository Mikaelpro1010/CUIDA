<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnexosManifestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anexos_manifest', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_manifestacao')->unsigned();
            $table->foreign('id_manifestacao')->references('id')->on('manifests');
            $table->integer('ci_anexo');
            $table->string('caminho');
            $table->string('nome');
            $table->string('extensao');
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
        Schema::dropIfExists('anexos');
    }
}
