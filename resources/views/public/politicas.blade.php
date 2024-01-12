<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('titulo', 'CUIDA')</title>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" nonce="{{ csp_nonce() }}">
</head>
<body class="dark:bg-black">
     <!-- Inicio Navbar -->
     <nav class="navbar bg-second-color dark:bg-black">
        <div class="navbar-content bg-second-color dark:bg-black">
            <img src="{{ asset('imgs/logo/logo.png') }}" nonce="{{ csp_nonce() }}" alt="Celke" class="logo">
        </div>
        
        <div class="navbar-content dark:bg-black">
            <a href="{{route('login')}}" class="text-white">Login</a>
        </div>
    </nav>
    <!-- Fim Navbar -->
    <div class="container mt-3">
        <div class="card shadow p-3">
            <h1>Política Privacidade</h1>
            <hr>
            <p>
                A sua privacidade é importante para nós. É política do CUIDA respeitar a sua privacidade em relação a qualquer
                informação sua que possamos coletar no site e aplicativo <a href={{ env('APP_URL') }} />CUIDA</a>, e outros
                sites que possuímos e operamos.
            </p>
            <p>
                Buscamos coletar informações pessoais apenas quando absolutamente necessário para fornecer serviços, sempre de maneira 
                justa e legal, com sua ciência e consentimento. Informamos claramente os motivos da coleta e como as informações serão 
                utilizadas.
            </p>
            <p>
                Retemos apenas as informações pelo tempo necessário para atender às solicitações de serviços, protegendo-as adequadamente 
                contra perdas, roubos, acesso não autorizado, divulgação ou modificação.
            </p>
            <p>
                Não compartilhamos publicamente informações de identificação pessoal, a menos que exigido por lei. Respeitamos sua privacidade.
            </p>
            <p>
                Em nosso site, podem haver links para sites externos que não operamos. Ressaltamos que não controlamos o conteúdo e as práticas 
                de privacidade desses sites e não nos responsabilizamos por suas políticas.
            </p>
            <p>
                Você tem o direito de recusar fornecer informações pessoais, compreendendo que, em alguns casos, isso pode afetar a disponibilidade 
                de determinados serviços.
            </p>
            <p>
                Ao continuar usando nosso site, consideraremos que você aceita nossas práticas relacionadas ao Aviso de Privacidade e informações pessoais. 
                Caso tenha dúvidas sobre como tratamos dados do usuário e informações pessoais, não hesite em entrar em contato conosco. 
                Estamos à disposição para esclarecimentos.
            </p>
            
            <h2>Política de Cookies CUIDA</h2>
            
            <h3>O que são cookies?</h3>
            <p>
                Assim como a maioria dos sites profissionais, este site utiliza cookies, que são pequenos arquivos baixados 
                em seu dispositivo para aprimorar sua experiência de navegação. Nesta seção, detalhamos as informações que 
                esses cookies coletam, explicamos sua utilidade e fornecemos razões pelas quais armazenamos esses dados. 
                Além disso, compartilhamos informações sobre como você pode optar por não permitir o armazenamento de cookies, 
                embora isso possa afetar a funcionalidade do site.
            </p>
            
            <h3>Como usamos os cookies?</h3>
            <p>
                Empregamos cookies por diversas razões, as quais são explicadas a seguir. Infelizmente, em muitas situações, 
                não há opções padrão do setor que permitam desativar os cookies sem comprometer integralmente a funcionalidade 
                e os recursos aprimorados que eles proporcionam a este site. Sugerimos que mantenha todos os cookies ativos caso 
                não tenha certeza sobre a necessidade deles, especialmente se estiverem vinculados à prestação de um serviço que você utiliza.
            </p>
            
            <h3>Desativar cookies</h3>
            <p>
                Você pode impedir a configuração de cookies ajustando as configurações do seu navegador (consulte a Ajuda do
                navegador para saber como fazer isso). Esteja ciente de que a desativação de cookies afetará a funcionalidade deste
                e de muitos outros sites que você visita. A desativação de cookies geralmente resultará na desativação de
                determinadas funcionalidades e recursos deste site. Portanto, é recomendável que você não desative os cookies.
            </p>
            
            <h3>Cookies que definimos</h3>
            <ul>
                <li>
                    Cookies relacionados à conta:
                    <br><br>
                    Se optar por criar uma conta conosco, empregaremos cookies para facilitar o processo de inscrição e a gestão geral da sua conta. 
                    Geralmente, esses cookies são excluídos ao sair do sistema; no entanto, em alguns casos, podem ser retidos para preservar suas 
                    preferências no site após o logout.
                    <br><br>
                </li>
                <li>
                    Cookies relacionados ao login:
                    <br><br>
                    Quando você realiza o login, utilizamos cookies para recordar dessa ação, eliminando a necessidade de fazer login a cada nova página visitada. 
                    Esses cookies são comumente removidos ao efetuar o logout, garantindo que o acesso a recursos e áreas restritas seja restrito apenas a períodos 
                    em que estiver logado.
                    <br><br>
                </li>
                <li>
                    Cookies relacionados a formulários:
                    <br><br>
                    Ao enviar dados por meio de formulários, como os encontrados nas páginas de contato ou nos formulários de comentários, cookies podem ser configurados 
                    para preservar os detalhes do usuário para referências futuras.
                    <br><br>
                </li>
                <li>
                    Cookies de preferências do site:
                    <br><br>
                    Com o objetivo de proporcionar uma experiência otimizada neste site, oferecemos a funcionalidade para personalizar suas preferências em relação ao funcionamento do site. 
                    Para lembrar essas preferências, é necessário configurar cookies, permitindo que as informações sejam recuperadas sempre que você interagir com páginas afetadas por suas escolhas.
                    <br>
                </li>
            </ul>
            
            <h3>Cookies de Terceiros</h3>
            <p>
                Em situações específicas, também implementamos cookies provenientes de fontes externas confiáveis. 
                A seção a seguir fornece detalhes sobre os cookies de terceiros que podem ser encontrados neste site.
            </p>
            <ul>
                <li>
                    Utilizamos o Google Analytics, uma das soluções de análise mais amplamente reconhecidas e confiáveis da web, 
                    para compreender como os visitantes interagem com o site e identificar oportunidades de aprimoramento na experiência do usuário. 
                    Esses cookies podem monitorar aspectos como a duração da visita ao site e as páginas acessadas, possibilitando a contínua produção de conteúdo envolvente.
                </li>
            </ul>
            
            <h3>Compromisso do Usuário</h3>
            <p>
                O usuário se compromete a fazer uso adequado dos conteúdos e da informação que o CUIDA oferece no site e com
                caráter enunciativo, mas não limitativo:
            </p>
            <ul>
                <li>
                    A) Não se envolver em atividades que sejam ilegais ou contrárias à boa fé a à ordem pública;
                </li>
                <li>
                    B) Não difundir propaganda ou conteúdo de natureza racista, xenofóbica, ou azar, qualquer tipo de pornografia
                    ilegal, de apologia ao terrorismo ou contra os direitos humanos;
                </li>
                <li>
                    C) Não causar danos aos sistemas físicos (hardwares) e lógicos (softwares) do CUIDA, de seus fornecedores
                    ou terceiros, para introduzir ou disseminar vírus informáticos ou quaisquer outros sistemas de hardware ou
                    software que sejam capazes de causar danos anteriormente mencionados.
                </li>
            </ul>
            
            <h3>Mais informações</h3>
            <p>
                Esperamos que as informações estejam claras, e como destacado anteriormente, se houver alguma dúvida sobre a necessidade de determinados cookies, 
                é aconselhável manter a opção de cookies ativada, especialmente se você estiver interagindo com algum dos recursos disponíveis em nosso site.
            </p>
            <p>Esta política é efetiva a partir de <strong>Janeiro</strong>/<strong>2024</strong>.</p>
        </div>
    </div>
    <script src="{{ asset('js/scripts.js') }}" nonce="{{ csp_nonce() }}" data-auto-add-css="false"></script>
</body>
