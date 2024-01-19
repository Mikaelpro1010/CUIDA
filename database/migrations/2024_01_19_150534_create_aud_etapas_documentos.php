<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudEtapasDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aud_etapas_documentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->string('icone');
            $table->string('lado_timeline');
            $table->integer('cadastrado_por');
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
        Schema::dropIfExists('aud_etapas_documentos');
    }
}
