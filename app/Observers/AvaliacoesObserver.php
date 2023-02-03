<?php

namespace App\Observers;

use App\Models\Avaliacao\Avaliacao;
use App\Models\Avaliacao\Unidade;

class AvaliacoesObserver
{
    /**
     * Listen to the Avaliacao created event.
     *
     * @param  \App\Avaliacao $avaliacao
     * @return void
     */
    public function created(Avaliacao $avaliacao): void
    {
        // $setor = Setor::find($avaliacao->setor_id);
        // $setor->updateResumoCache($avaliacao);

        // $unidade = Unidade::find($setor->unidade_id);
        // $unidade->updateResumoCache($avaliacao);
    }
}
