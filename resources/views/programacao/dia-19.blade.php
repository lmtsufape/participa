@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="display-4 mb-4 fw-bold" style="color: #3d93a9; border-bottom: 3px solid #f2a440; padding-bottom: 10px;">Programação — 19/11/2026</h1>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Grupos de Trabalho - 08:00 às 12:00</h3>
                @include('programacao.local', ['local' => "FAFICH e ECI"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Atração artística – 12:00 às 13:00</h3>
                <p class="fw-bold mb-3">Mestre Faria e o Boi Livre - cortejo festivo</p>
                @include('programacao.local', ['local' => "Praça de Serviços"])
            </section>
            <h2 class="h3 mt-5 mb-4" style="color: #3d93a9;">Mesas-redondas</h2>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 14- 13:00 às 15:00</h3>
                <p class="fw-bold mb-3">Dimensões humanas da conservação: análise da percepção social sobre comunidades tradicionais</p>
                <p class="mb-2">Carolina Alves (UFG)</p>
                <p class="mb-2">Luiza Soares Sotto (UFG)</p>
                <p class="mb-2">Michele Duarte (IF Goiano)</p>
                @include('programacao.local', ['local' => "Auditório Baesse (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 15- 13:00 às 15:00</h3>
                <p class="fw-bold mb-3">Etnobiologia em Abya Yala: memória biocultural e decolonialidade</p>
                <p class="mb-2">Ana Haydée Ladio (UNCOMA, Argentina)</p>
                <p class="mb-2">Bibiana Leonor Vilá (CONICET, Argentina)</p>
                <p class="mb-2">Erendira Juanita Cano Contreras (SOLAE; UAB, Espanha)</p>
                <p class="mb-2">Ramón Mariaca Méndez (El Colegio de la Frontera Sur, México)</p>
                <p class="mb-2">Rumi Regina Kubo (UFRGS)</p>
                <p class="mb-2">Álvaro Sepúlveda-Pío (Sociedad Colombiana de Etnobiología)</p>
                @include('programacao.local', ['local' => "Auditório Carangola (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 16- 13:00 às 15:00</h3>
                <p class="fw-bold mb-3">As “expedições científicas” com Etnobiologia dentro da iniciativa Amazônia+10</p>
                <p class="mb-2">Bernardo Tomchinsky (UNIFESSPA)</p>
                <p class="mb-2">Moacir Haverroth (EMBRAPA)</p>
                <p class="mb-2">Viviane Stern da Fonseca-Kruel (UFRJ, Jardim Botânico do Rio de Janeiro)</p>
                <p class="mb-2">Márlia Coelho Fereira (Museu Paraense Emílio Goeldi)</p>
                <p class="mb-2">Lin Chau Ming (UFAM)</p>
                <p class="mb-2">Juarez Pezzuti (UFPA)</p>
                @include('programacao.local', ['local' => "Auditório Bicalho (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 17- 15:30 às 17:30</h3>
                <p class="fw-bold mb-3">Pescadores e mamíferos aquáticos: a Etnobiologia como ferramenta para a pesquisa e a conservação de espécies amazônicas ameaçadas de extinção</p>
                <p class="mb-2">Marcelo Derzi Vidal (ICMBio)</p>
                <p class="mb-2">Gustavo Hallwass (UFLA)</p>
                <p class="mb-2">Renata Emin-Lima (Museu Paraense Emílio Goeld)</p>
                @include('programacao.local', ['local' => "Auditório Bicalho (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 18- 15:30 às 17:30</h3>
                <p class="fw-bold mb-3">Programa nacional CNPq/ MCTI sobre pesquisas interculturais: uma contrução popular</p>
                <p class="mb-2">Gustavo Taboada Soldati (UFJF)</p>
                <p class="mb-2">Sofia Zank (UFSC)</p>
                <p class="mb-2">Natália Hanazaki (UFSC)</p>
                <p class="mb-2">Nivaldo Peroni (UFSC)</p>
                <p class="mb-2">Bruno Marangoni Martinelli (MCTI)</p>
                <p class="mb-2">Cristiane Gomes Julião Pankararu (Câmara Setorial das Guardiães e dos Guardiões da Biodiversidade - CSG)</p>
                <p class="mb-2">Marciano Toledo (Câmara Setorial das Guardiães e dos Guardiões da Biodiversidade -CSG)</p>
                <p class="mb-2">Lidiane Taverny (Rede dos Povos e Comunidades Tradicionais do Brasil)</p>
                <p class="mb-2">Ana Paula Santos (Rede dos Povos e Comunidades Tradicionais do Brasil)</p>
                <p class="mb-2">Mabel Apurinã (Coordenação das Organizações Indígenas da Amazônia Brasileira - COIAB)</p>
                @include('programacao.local', ['local' => "Auditório Carangola (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 19- 15:30 às 17:30</h3>
                <p class="fw-bold mb-3">Racismo ambiental no Brasil: raízes históricas, conflitos territoriais e implicações para a justiça socioambiental</p>
                <p class="mb-2">Cristiano Bento da Silva (UNIFESSPA)</p>
                <p class="mb-2">Danielly Cristina Pereira Vieira (UNICENTRO)</p>
                <p class="mb-2">Joelson Moreno Brito de Moura (UNIFESSPA)</p>
                <p class="mb-2">Regina Célia da Silva Oliveira (UNIVASF)</p>
                @include('programacao.local', ['local' => "Auditório Baesse (FAFICH)"])
            </section>
            <h2 class="h3 mt-5 mb-4" style="color: #3d93a9;">Simpósios Especiais</h2>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Simpósio Especial 7 - 13:00 às 17:30</h3>
                <p class="fw-bold mb-3">Pesca artesanal no Brasil: racismo ambiental, violações de direitos, epistemologias situadas e práticas de cuidado</p>
                <p class="mb-2">Clara Cabral Noronha (UFBA)</p>
                <p class="mb-2">Gabriela Teixeira Vanegas De La Rosa (UFBA)</p>
                <p class="mb-2">Jéssica Sacramento da Hora (INCT IN-TREE)</p>
                <p class="mb-2">Taiala Viviane Menezes de Jesus (UFBA)</p>
                <p class="mb-2">Adriana Maria Espinoza Fernando (EMBRAPA – Pantanal)</p>
                <p class="mb-2">Ana Paula Thé (UNIMONTES)</p>
                <p class="mb-2">Gustavo Goulart Moreira Moura (UFPA)</p>
                <p class="mb-2">Gilberto Lopes dos Reis (Comunidade Pesqueira e Vazanteira da Venda - Pedras de maria da Cruz/MG)</p>
                <p class="mb-2">Letícia Aparecida Rocha (Conselho Pastoral dos Pescadores - CPP)</p>
                <p class="mb-2">Maria das Dores Pereira Silva (Comunidade Tradicional Quilombola, Pesqueira e Vazanteira de Croatá, Januária/MG)</p>
                @include('programacao.local', ['local' => "Auditório 3 – Cerrado – Instituto de Ciências Biológicas"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Simpósio Especial 8 - 13:00 às 17:30</h3>
                <p class="fw-bold mb-3">Sociedade Brasileira de Etnobiologia e Etnoecologia: 30 anos</p>
                <p class="mb-2">Gabriela Coelho de Souza (UFRGS)</p>
                <p class="mb-2">Eraldo Medeiro Costa Neto (UEFS)</p>
                <p class="mb-2">Regina Rumi Kubo (UFRGS)</p>
                <p class="mb-2">Emmanuel Duarte Almada (UFMG)</p>
                <p class="mb-2">Ulysses Paulino de Albuquerque (UFPE)</p>
                <p class="mb-2">Lin Chau Ming (UFAM)</p>
                <p class="mb-2">Flávio Bezerra Barros (UFPA)</p>
                <p class="mb-2">Gustavo Taboada Soldati (UFJF)</p>
                @include('programacao.local', ['local' => "Auditório do Instituto de Geociências (IGC)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Cerimônia de Encerramento 18:30 às 20:00</h3>
                @include('programacao.local', ['local' => "Auditório CAD 1"])
            </section>
        </div>
    </div>
</div>
@endsection
