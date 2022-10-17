<?php

namespace App\Console\Commands;

use App\Models\Avaliacao\Unidade;
use App\Models\Secretaria;
use Illuminate\Console\Command;

class UpdateNotasFromUnidadesAndSecretarias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateNotasFromUnidadesAndSecretarias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza as notas de todas as Unidades e de todas as Secretarias';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $unidades = Unidade::query()->with('avaliacoes')->get();

        foreach ($unidades as $unidade) {
            $this->info($unidade->nome);
            $unidade->update([
                'nota' => $unidade->avaliacoes->avg('nota')
            ]);
        }

        $secretarias = Secretaria::query()->with('unidades')->get();
        foreach ($secretarias as $secretaria) {
            $this->info($secretaria->sigla);
            $secretaria->update([
                'nota' => $secretaria->unidades->avg()
            ]);
        }
    }
}
