<?php

use App\Models\Secretaria;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SecretariasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Secretaria::query()->insert([
            [
                // 'id' => 1,
                'nome' => 'Secretaria do Planejamento e Gestão',
                'sigla' => 'SEPLAG',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 2,
                'nome' => 'Secretaria do Urbanismo e Meio Ambiente',
                'sigla' => 'SEUMA',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 3,
                'nome' => 'Procuradoria Geral do Município',
                'sigla' => 'PGM',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 4,
                'nome' => 'Gabinete do Prefeito',
                'sigla' => 'GABPREF',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 5,
                'nome' => 'Gabinete Vice Prefeitura',
                'sigla' => 'GABVICE',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 6,
                'nome' => 'Secretaria de Educação',
                'sigla' => 'SME',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 7,
                'nome' => 'Secretaria da Saúde',
                'sigla' => 'SMS',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 8,
                'nome' => 'Secretaria da Juventude, Esporte e Lazer',
                'sigla' => 'SECJEL',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 9,
                'nome' => 'Secretaria das Finanças',
                'sigla' => 'SEFIN',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 10,
                'nome' => 'Secretaria de Trabalho e Desenvolvimento Econômico',
                'sigla' => 'STDE',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 11,
                'nome' => 'Secretaria dos Direitos Humanos, Habitação e Assist. Social',
                'sigla' => 'SEDHAS',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 12,
                'nome' => 'Agência Municipal do Meio Ambiente',
                'sigla' => 'AMA',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 13,
                'nome' => 'Secretaria de Serviços Públicos',
                'sigla' => 'SESEP',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 14,
                'nome' => 'Secretaria de Infraestrutura',
                'sigla' => 'SEINF',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 15,
                'nome' => 'Secretaria da Segurança Cidadã',
                'sigla' => 'SESEC',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 16,
                'nome' => 'Secretaria da Cultura e Turismo',
                'sigla' => 'SECULT',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 17,
                'nome' => 'Secretaria do Trânsito e Transportes',
                'sigla' => 'SETRAN',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 18,
                'nome' => 'Controladoria e Ouvidoria geral do Município',
                'sigla' => 'CGM',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 19,
                'nome' => 'Central de Licitações',
                'sigla' => 'CELIC',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                // 'id' => 20,
                'nome' => 'Serviço Autônomo de Água e Esgoto de Sobral',
                'sigla' => 'SAAE',
                'nota' => 0,
                'ativo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
