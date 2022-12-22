<?php

use App\Models\Avaliacao\TipoAvaliacao;
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
        TipoAvaliacao::insert([
            [
                'nome' => "Atendimento",
                'pergunta' => 'Como gostaria de avaliar nosso atendimento?',
                'obrigatorio' => true,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nome' => "Estrutura",
                'pergunta' => 'Gostaria de avaliar nossa estrutura (Local, móveis) ?',
                'obrigatorio' => false,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
