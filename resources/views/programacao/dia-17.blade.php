@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="display-4 mb-4 fw-bold" style="color: #3d93a9; border-bottom: 3px solid #f2a440; padding-bottom: 10px;">Programação — 17/11/2026</h1>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Grupos de Trabalho - 08:00 às 12:00</h3>
                @include('programacao.local', ['local' => "FAFICH e ECI"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Sessão de Pôsteres – 17:30 às 18:30</h3>
                @include('programacao.local', ['local' => "FAFICH – Hall do 1° andar"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mostra Audiovisual – 17:30 às 18:30 e 19:30 às 21:30</h3>
                @include('programacao.local', ['local' => "Auditório da Escola de Belas Artes (EBA)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Lançamento de Livros - 19:30 às 21:30</h3>
                @include('programacao.local', ['local' => "Auditório Bicalho (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Atração artística – 12:00 às 13:00</h3>
                <p class="fw-bold mb-3">Vivência Cultural com Mestre Farinhada, educador popular:</p>
                <p class="mb-2">Cantigas de Roda, Calango, Poesia e Histórias da Cultura Popular de Minas Gerais</p>
                @include('programacao.local', ['local' => "Praça de Serviços"])
            </section>
            <h2 class="h3 mt-5 mb-4" style="color: #3d93a9;">Mesas-redondas</h2>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 1 - 13:00 às 15:00</h3>
                <p class="fw-bold mb-3">Territórios, povos tradicionais e a sociobiodiversidade brasileira na promoção e fortalecimento da segurança alimentar e nutricional</p>
                <p class="mb-2">Flávio Bezerra Barros (UFPA)</p>
                <p class="mb-2">Gabriela Coelho de Souza (UFRGS)</p>
                <p class="mb-2">Renata Paulina Rosa (Quilombo de Pontinha - Paraopeba/MG)</p>
                <p class="mb-2">Normelia Gonçalves Batista Moreira (Quilombo de Pontinha - Paraopeba/MG)</p>
                <p class="mb-2">Regina Barros Goulart Nogueira (Fórum Nacional de Segurança Alimentar e Nutricional dos Povos Tradicionais de Matriz Africana)</p>
                @include('programacao.local', ['local' => "Auditório Baesse (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 2 - 13:00 às 15:00</h3>
                <p class="fw-bold mb-3">Crimes da mineração: renovação das violências e respostas dos territórios</p>
                <p class="mb-2">Maryellen Milena de Lima (GESTA/UFMG)</p>
                <p class="mb-2">Sérgio Papagaio (Associação dos Garimpeiros e Garimpeiras Tradicionais do Alto Rio Doce</p>
                <p class="mb-2">Baba Edvaldo de Jesus (PCTRAMA - Coletivo Povos e Comunidades de Tradição Religiosa Ancestral de Matriz Africana)</p>
                <p class="mb-2">Kenya Pataxó (Comunidade Indígena Pataxó Hierú TGA)</p>
                <p class="mb-2">Ana na Mesa Redonda (Quilombola de Queimadas, Serro/MG)</p>
                <p class="mb-2">Adair Pereira de Almeida (Conselho Intermunicipal Comunitário do Território Tradicional Geraizeiro e Vacariano do Vale das Cancelas – Grão Mongol/MG)</p>
                @include('programacao.local', ['local' => "Auditório Carangola (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 3 - 13:00 às 15:00</h3>
                <p class="fw-bold mb-3">Encontro de saberes e diálogos interepistêmicos</p>
                <p class="mb-2">Rafael Fares (Programa Encontro de Saberes – UEMG)</p>
                <p class="mb-2">Maria Aparecida Moura (Saberes Tradicionais – UFMG)</p>
                <p class="mb-2">Mestre Dirceu Pereira Sérgio (Irmandade de Nossa Senhora do Rosário de Justinópolis , Ribeirão das Neves/MG)</p>
                <p class="mb-2">Pedrina de Lourdes Santos (Guarda de Massambique de Nossa Senhora das Mercês de Oliveira, MG)</p>
                <p class="mb-2">Daru Tikuna (Mestra Saberes Tradicionais – UFMG/UEMG)</p>
                <p class="mb-2">Flávio dos Santos (Comunidade Quilombola do Açude – Jaboticatubas/MG, Secretaria de Cultura de Santana do Riacho/MG)</p>
                @include('programacao.local', ['local' => "Auditório Bicalho (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 4 - 13:00 às 15:00</h3>
                <p class="fw-bold mb-3">Etnoconservação da natureza: 25 anos depois</p>
                <p class="mb-2">Gustavo Goulart Moreira Moura (UFPA)</p>
                <p class="mb-2">Sueli Angelo Furlan (USP)</p>
                <p class="mb-2">Roberto Adrian Ribaric (USP)</p>
                <p class="mb-2">Ana Paula Diegues (USP)</p>
                @include('programacao.local', ['local' => "Sala 1204 Teleconferências (Faculdade de Educação)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 5 - 15:30 às 17:30</h3>
                <p class="fw-bold mb-3">Etnobiologia na escola: saberes, poder e emancipação</p>
                <p class="mb-2">Ângelo Giuseppe Chaves Alves (UFRPE)</p>
                <p class="mb-2">Elizete de Lima Queiroz (Povo Gavião Parkatêjê - Escola Estadual Indígena Peptykre Parkatêjê - Aldeia Parkatêjê)</p>
                <p class="mb-2">Pai Ricardo de Moura (Casa de Caridade Pai Jacó do Oriente)</p>
                <p class="mb-2">Jõprãmre Rãrãkre Jõkumti (ParkatêjêPovo Gavião Parkatêjê - Escola Estadual Indígena Peptykre Parkatêjê - Aldeia Parkatêjê)</p>
                <p class="mb-2">Maiara Cristina Gonçalves (UFSC)</p>
                <p class="mb-2">Taline Cristina da Silva (UNEAL)</p>
                @include('programacao.local', ['local' => "Auditório Carangola (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 6 - 15:30 às 17:30</h3>
                <p class="fw-bold mb-3">Domesticação e manejo da agrobiodiversidade</p>
                <p class="mb-2">Carolina Levis (UFSC)</p>
                <p class="mb-2">Maria Júlia Ferreira (INPA)</p>
                <p class="mb-2">Nivaldo Peroni (UFSC)</p>
                <p class="mb-2">Tiago Hermenegildo (UFMG)</p>
                @include('programacao.local', ['local' => "Auditório Bicalho (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 7 - 15:30 às 17:30</h3>
                <p class="fw-bold mb-3">Povos e comunidades tradicionais em Minas Gerais: violações e luta por direitos</p>
                <p class="mb-2">João Pio (Quilombo dos Arturos)</p>
                <p class="mb-2">Maxuell Moreira Pio (Comunidade Tradicional Carroceira – RMBH)</p>
                <p class="mb-2">Aderval Costa Filho (UFMG)</p>
                <p class="mb-2">Maria de Fátima Alves (Comissão em Defesa dos Direitos das Comunidades Extrativistas Apanhadoras de Flores Sempre-Vivas – CODECEX)</p>
                <p class="mb-2">Adair Pereira de Almeida (Conselho Intermunicipal Comunitário do Território Tradicional Geraizeiro e Vacariano do Vale das Cancelas – Grão Mongol/MG)</p>
                @include('programacao.local', ['local' => "Auditório Baesse (FAFICH)"])
            </section>
            
            <h2 class="h3 mt-5 mb-4" style="color: #3d93a9;">Simpósios Especiais</h2>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Simpósio Especial 1 - 13:00 às 17:30</h3>
                <p class="fw-bold mb-3">Etnobiologia Evolutiva</p>
                <p class="mb-2">Flávia Rosa Santoro (UENF)</p>
                <p class="mb-2">Patrícia Muniz de Medeiros (UFAL)</p>
                <p class="mb-2">Washington Soares Ferreira Júnior (UPE)</p>
                <p class="mb-2">Ulysses Paulino de Albuquerque (UFPE)</p>
                <p class="mb-2">Joelson Moreno Brito de Moura (UNIFESSPA)</p>
                <p class="mb-2">André Luiz Borba Nascimento (UFMA)</p>
                <p class="mb-2">Ana Haydée Ladio (UNCOMA, Argentina)</p>
                @include('programacao.local', ['local' => "Auditório Adriana Bogliolo (Escola de Ciência da Informação)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Simpósio Especial 2 - 13:00 às 17:30</h3>
                <p class="fw-bold mb-3">Sistemas Agrícolas Tradicionais: pesquisas, políticas públicas e salvaguarda do patrimônio biocultural</p>
                <p class="mb-2">Ana Paula Glinfskoi Thé (UNIMONTES)</p>
                <p class="mb-2">Breno Trindade da Silva (IEPHA-MG)</p>
                <p class="mb-2">Fernanda Testa Monteiro (Grupo de Estudos em Territorialidades e Sistemas Agrícolas Tradicionais - UFVJM)</p>
                <p class="mb-2">Maria de Fátima Alves (Comissão em Defesa dos Direitos das Comunidades Extrativistas Apanhadoras de Flores Sempre-Vivas – CODECEX)</p>
                <p class="mb-2">Flávia Maria Galizoni (UFMG)</p>
                <p class="mb-2">Fábio de Oliveira Freitas (EMBRAPA)</p>
                <p class="mb-2">Enedina Souza dos Santos (Comunidade Tradicional Quilombola, Pesqueira e Vazanteira de Croatá)</p>
                @include('programacao.local', ['local' => "Auditório 3 – Cerrado – Instituto de Ciências Biológicas"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Simpósio Especial 3 - 13:00 às 17:30</h3>
                <p class="fw-bold mb-3">Áreas protegidas e territórios tradicionais: tensões e limites da gestão territorial</p>
                <p class="mb-2">Ana Beatriz Viana Mendes (UFMG)</p>
                <p class="mb-2">Henyo Trindade Barreto Filho (UnB)</p>
                <p class="mb-2">Frederico Drumond Martins (ICMBIO)</p>
                <p class="mb-2">Gleidiane de Freitas Souza (Comissão em Defesa dos Direitos das Comunidades Extrativistas Apanhadoras de Flores Sempre-Vivas – CODECEX)</p>
                <p class="mb-2">Luciano Josino de Araújo (Liderança Médio São Francisco)</p>
                <p class="mb-2">Zilomar (Serra da Canastra/MG)</p>
                <p class="mb-2">Gisele Siqueira (Comunidade do Retiro - Serra do Cipó/MG)</p>
                <p class="mb-2">Raquel Inácio Aparício (Comunidade do Retiro - Serra do Cipó/MG)</p>
                <p class="mb-2">Andrea Zanetti (Serra da Canastra/MG)</p>
                <p class="mb-2">Renata Almeida dos Santos (ICMBio)</p>
                <p class="mb-2">Gabriela Almeida dos Santos (ICMBio)</p>
                <p class="mb-2">Maria Aparecida Alves Monteiro (Comunidade do Retiro - Serra do Cipó/MG)</p>
                @include('programacao.local', ['local' => "Auditório da Escola de Belas Artes (EBA)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Simpósio Especial 4 (Seção 1) - 13:00 às 17:30</h3>
                <p class="fw-bold mb-3">Práticas tradicionais de cura e cuidado: lutas, visibilidade e descriminalização</p>
                <p class="mb-2">Sabrina Almeida (UFMG)</p>
                <p class="mb-2">Silvia Guimarães (UnB)</p>
                <p class="mb-2">Cleonice Pankararu (Aldeia Cinta Vermelha-Jundiba, Araçuaí/MG)</p>
                <p class="mb-2">Shirlene Sabino (Comunidade Quilombola do Açude, Jaboticatubas/MG)</p>
                <p class="mb-2">Crisângela Elen de Souza (Articulação Embaúba)</p>
                <p class="mb-2">Dona Maria dos Remédios (Morro do pilar/MG)</p>
                <p class="mb-2">Aparecida Arruda (Ervanário São Francisco de Assis, Sabará/MG)</p>
                <p class="mb-2">Vivian Camacho (Salud de los Pueblos, Bolívia)</p>
                @include('programacao.local', ['local' => "Auditório do Instituto de Geociências (IGC)"])
            </section>
            <h2 class="h3 mt-5 mb-4" style="color: #3d93a9;">Palestras</h2>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Palestra 1 - 18:30 às 19:30</h3>
                <p class="fw-bold mb-3">O que a etnobiologia ainda não consegue ver?</p>
                <p class="mb-2">Ulysses Paulino de Albuquerque (UFPE)</p>
                @include('programacao.local', ['local' => "Auditório 3 – Cerrado – Instituto de Ciências Biológicas"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Palestra 2 - 18:30 às 19:30</h3>
                <p class="fw-bold mb-3">Medicina tradicional ancestral para el cuidado intregral de la vida</p>
                <p class="mb-2">Vivian Camacho (Salud de los Pueblos, Bolívia)</p>
                @include('programacao.local', ['local' => "Auditório Carangola (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Palestra 3 - 18:30 às 19:30</h3>
                <p class="fw-bold mb-3">De águas a água - conhecimentos tradicionais, políticas públicas e clima no Seminário mineiro (Jequitinhonha e Norte).</p>
                <p class="mb-2">Flávia Maria Galizoni (UFMG)</p>
                @include('programacao.local', ['local' => "Auditório Adriana Bogliolo (Escola de Ciência da Informação)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Palestra 4 - 18:30 às 19:30</h3>
                <p class="fw-bold mb-3">La Etnobiología y la Agroecología que necesitamos en Latinoamérica&quot;</p>
                <p class="mb-2">Ramón Mariaca Méndez (El Colegio de la Frontera Sur, México)</p>
                @include('programacao.local', ['local' => "Auditório da Escola de Belas Artes (EBA)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Palestra 5 - 18:30 às 19:30</h3>
                <p class="fw-bold mb-3">Valorizaciones múltiples de los camélidos em las comunidades andinas de la Puna de Argentina</p>
                <p class="mb-2">Bibiana Leonor Vilá (CONICET, Argentina)</p>
                @include('programacao.local', ['local' => "Auditório Bicalho - FAFICH"])
            </section>
        </div>
    </div>
</div>
@endsection
