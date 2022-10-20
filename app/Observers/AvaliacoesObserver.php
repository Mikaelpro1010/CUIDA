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
        $unidade = Unidade::find($avaliacao->unidade_secr_id);
        $unidade->updateResumoCache($avaliacao);
    }
}
