<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aud_documentos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('aud_tipo_documento_id');
            $table->foreign('aud_tipo_documento_id')->references('id')->on('aud_tipos_documentos');
            $table->unsignedBigInteger('aud_status_documento_id');
            $table->foreign('aud_status_documento_id')->references('id')->on('aud_status_documentos');
            $table->unsignedBigInteger('aud_etapa_documento_id');
            $table->foreign('aud_etapa_documento_id')->references('id')->on('aud_etapas_documentos');
            $table->unsignedBigInteger('aud_prazo_documento_id');
            $table->foreign('aud_prazo_documento_id')->references('id')->on('aud_prazos_documentos');
            $table->unsignedBigInteger('aud_secretaria_id');
            $table->foreign('aud_secretaria_id')->references('id')->on('aud_secretarias');
            $table->string('descricao');
            $table->string('numero_spu');
            $table->date('data_abertura_spu');
            $table->date('data_relatorio');
            $table->string('numero_oficio');
            $table->date('data_envio_relatorio');
            $table->string('obs_sobre_oficio');
            $table->string('dias_consideracao_orgao');
            $table->string('processo_encerrado');
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->date('data_consideracoes_orgao');
            $table->string('posicao_atual');
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
        Schema::dropIfExists('aud_documentos');
    }
}
