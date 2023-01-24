<?php

use App\Models\Avaliacao\TipoAvaliacao;
use App\Models\Secretaria;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TiposAvaliacaoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $secretarias = Secretaria::all();
        foreach ($secretarias as $secretaria) {
            TipoAvaliacao::insert([
                [
                    'secretaria_id' => $secretaria->id,
                    'nome' => "Atendimento",
                    'pergunta' => 'Como gostaria de avaliar nosso atendimento?',
                    'obrigatorio' => true,
                    'default' => true,
                    'ativo' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'secretaria_id' => $secretaria->id,
                    'nome' => "Estrutura",
                    'pergunta' => 'Gostaria de avaliar nossa estrutura (de pessoal, física)?',
                    'obrigatorio' => true,
                    'default' => true,
                    'ativo' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]);
        }
    }
}
