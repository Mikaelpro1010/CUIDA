<?php

use App\Models\Avaliacao\Setor;
use App\Models\Avaliacao\TipoAvaliacao;
use App\Models\Avaliacao\Unidade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnidadesSecrTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(Unidade::class, 50)->create();

        $nomeOficial = [
            "Centro de Saúde da Família Professora Norma Soares",
            "Centro de Saúde da Família Maria Florêncio de Assis Romão",
            "Centro de Saúde da Família Francisco Moura Vieira",
            "Centro de Saúde da Família João Abdelmoumem Melo",
            "Centro de Saúde da Família Terezinha Neves Vasconcelos",
            "Centro de Saúde da Família Dr Jurandir Pontes Carvalho Filho",
            "Centro de Saúde da Família Dr. Grijalba Mendes Carneiro",
            "Centro de Saúde da Família Dr. Guarani Mont'Alverne",
            "Centro de Saúde da Família Inácio Rodrigues Lima",
            "Centro de Saúde da Família Dona Maria Eglantine Ponte Guimarães",
            "Centro de Saúde da Família Gerardo Carneiro Hardy",
            "Centro de Saúde da Família Maria Adeodato",
            "Centro de Saúde da Família Dr. Estevam Ferreira da Ponte",
            "Centro de Saúde da Família Dr.José Nilson Ferreira Gomes",
            "Centro de Saúde da Família Herbert de Sousa",
            "Centro de Saúde da Família José Mendes Mont'Alverne",
            "Centro de Saúde da Família Dr. José Silvestre Cavalcante Coelho",
            "Centro de Saúde da Família Doutor Thomaz Corrêa Aragão",
            "Centro de Saúde da Família Cleide Cavalcante de Sales",
            "Unidade Básica de Saúde Dr. Luciano Adeodato",
            "Centro de Saúde da Família Everton Francisco Mendes Mont' Alverne",
            "Centro de Saúde da Família Francinilda de Sousa Mendes",
            "Centro de Saúde da Família Dr. Antonio de Pádua Neves",
            "Centro de Saúde da Família Leda Prado VI",
            "Centro de Saúde da Família de Aracatiaçu Leda Prado II",
            "Centro de Saúde da Família Antônio Herculano de Mesquita",
            "Centro de Saúde da Família Edmundo Rodrigues Freire",
            "Centro de Saúde da Família Maria Carmelita Andrade da Silva",
            "Centro de Saúde da Família Doutor Manoel Marinho",
            "Centro de Saúde da Família Deputado Padre José Linhares Ponte",
            "Centro de Saúde da Família Leda Prado",
            "Centro de Saúde da Família Leda Prado III",
            "Centro de Saúde da Família Francisco Pedro Firmino",
            "Centro de Saúde da Família José Salustiano Caixeiro",
            "Centro de Saúde da Família Rafael Arruda Leda Prado V",
            "Centro de Saúde da Família Maria Rosângela Rodrigues da Silva",
            "Centro de Saúde da Família de Taperuaba",
            "Centro de Saúde da Família Antônio Ribeiro da Silva",
            "",
            "",
            "",
            "",
            "",
            "",
        ];

        $nome = [
            'CSF - Alto da Brasília',
            'CSF - Alto do Cristo',
            'CSF - Caic ',
            'CSF - Caiçara ',
            'CSF - Campo dos Velhos',
            'CSF - Centro',
            'CSF - COELCE',
            'CSF - Cohab II',
            'CSF - Cohab III',
            'CSF - Dom Expedito',
            'CSF - Estação',
            'CSF - Expectativa',
            'CSF - Junco',
            'CSF - Novo Recanto',
            'CSF - Padre Palhano',
            'CSF - Pedrinhas',
            'CSF - Santo Antônio',
            'CSF - Sinhá Saboia',
            'CSF - Sumaré',
            'CSF - Tamarindo',
            'CSF - Terrenos Novos I',
            'CSF - Terrenos Novos II',
            'CSF - Vila União',
            'CSF - Aprazível',
            'CSF - Aracatiaçu',
            'CSF - Baracho',
            'CSF - Bilheira',
            'CSF - Bonfim',
            'CSF - Caioca',
            'CSF - Caracará',
            'CSF - Jaibaras',
            'CSF - Jordão',
            'CSF - Patos',
            'CSF - Patriarca',
            'CSF - Rafael Arruda',
            'CSF - Salgado dos Machados',
            'CSF - Taperuaba',
            'CSF - Torto',
            "Unidade de Apoio Barragem",
            "Unidade de Apoio Vassouras",
            "Unidade de Apoio São Francisco",
            "Unidade de Apoio Contendas",
            "Unidade de Apoio Ouro Branco",
            "Unidade de Apoio Recreio",
        ];

        $setores = [
            "Recepção / SAME",
            "Sala de Procedimentos",
            "Consultório de Enfermagem",
            "Consultório Médico",
            "Odontologia",
            "Farmácia",
            "Sala de Vacina",
            "Marcação de Consultas",
            "Atendimento Multiprofissional",
            "Gerência",
        ];

        $tiposAvaliacao = TipoAvaliacao::where('secretaria_id', 7)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => ['nota' => 0]];
            })
            ->toArray();
        logger($tiposAvaliacao);
        foreach ($nome as $key => $unidade) {

            $unidade = Unidade::create([
                'secretaria_id' => 7, //SMS
                'nome' => $unidade,
                'nome_oficial' => $nomeOficial[$key],
                'token' => substr(bin2hex(random_bytes(50)), 1),
                'nota' => 0,
                'ativo' => true,

            ]);

            $setorPrincipal = Setor::create([
                'unidade_id' => $unidade->id,
                'nome' => 'Avaliação Geral',
                'token' => substr(bin2hex(random_bytes(50)), 1),
                'nota' => 0,
                'ativo' => true,
                'principal' => true,
            ]);
            $setorPrincipal->tiposAvaliacao()->sync($tiposAvaliacao);

            foreach ($setores as  $setor) {
                $setor = Setor::query()->create([
                    'unidade_id' => $unidade->id,
                    'nome' => $setor,
                    'token' => substr(bin2hex(random_bytes(50)), 1),
                    'nota' => 0,
                    'ativo' => true,
                    'principal' => false,
                ]);
                $setor->tiposAvaliacao()->sync($tiposAvaliacao);
            }
        }
    }
}
