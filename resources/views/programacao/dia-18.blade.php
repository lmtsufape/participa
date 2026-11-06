@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="display-4 mb-4 fw-bold" style="color: #3d93a9; border-bottom: 3px solid #f2a440; padding-bottom: 10px;">Programação — 18/11/2026</h1>
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
                <h3 class="h4 mb-3" style="color: #3d93a9;">Assembleia da Sociedade Brasileira de Etnobiologia e Etnoecologia - 19:30 às 21:30</h3>
                @include('programacao.local', ['local' => "Auditório Carangola (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Atração artística – 12:00 às 13:00</h3>
                <p class="fw-bold mb-3">Sonoridades Afro-Diaspóricas — Apresentação musical com os alunos do CP</p>
                <p class="mb-2">Coordenação: Professor Claudio Santos (CP/UFMG)*</p>
                <p class="mb-2">*Quarta Doze e trinta - atividade promovida pela Pró-reitoria de Cultura</p>
                @include('programacao.local', ['local' => "Praça de Serviços"])
            </section>
            <h2 class="h3 mt-5 mb-4" style="color: #3d93a9;">Mesas-redondas</h2>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 8 - 13:00 às 15:00</h3>
                <p class="fw-bold mb-3">Entre ética, política e ciência: os desafios da pesquisa em Etnobiologia</p>
                <p class="mb-2">Juliana Loureiro de Almeida Campos (CESCO, França)</p>
                <p class="mb-2">Meredith Root-Bernstein (CESCO, França)</p>
                <p class="mb-2">Guillaume Odonne (CNRS, Guiana Francesa)</p>
                @include('programacao.local', ['local' => "Auditório Carangola (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 9 - 13:00 às 15:00</h3>
                <p class="fw-bold mb-3">Diversidade Biocultural nas Cidades</p>
                <p class="mb-2">Mariana Oliveira e Souza (UEMG)</p>
                <p class="mb-2">Sebastião Alves Lima (Comunidade Tradicional Carroceira – RMBH)</p>
                <p class="mb-2">Alexandra Assis (Movimento de Luta de  Bairros, Vilas e Favelas, Belo Horizonte/MG) MLB</p>
                <p class="mb-2">Álan Oziel da Silva Pires (Fundação Municipal de Cultura – PBH)</p>
                <p class="mb-2">Miriam Aprigio Pereira (Quilombo dos Luízes, BH/MG)</p>
                <p class="mb-2">Gabriel Luis de Souza (Diretor de Políticas de Reparação e Promoção da Igualdade Racial/PBH, Conselho de Promoção da Igualdade Racial)</p>
                @include('programacao.local', ['local' => "Auditório Baesse (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 10 - 13:00 às 15:00</h3>
                <p class="fw-bold mb-3">Antropoceno? Capitaloceno? E os povos tradicionais com isso, cara-pálida?</p>
                <p class="mb-2">Eleonice Sacramento (Movimento de Pescadores e Pescadoras Artesanais -MPP))</p>
                <p class="mb-2">Francisco José Bezerra Souto (UEFS)</p>
                <p class="mb-2">Marcelo Nivert Schlindewein (UFSCAR)</p>
                <p class="mb-2">Ernando Ferreira Pinto (Coordenação de Inclusão Produtiva e Etnodesenvolvimento Quilombola e de Povos e Comunidades Tradicionais (CGETNO) da SETEQ/MDA)</p>
                <p class="mb-2">Natalia Hanazaki (UFSC)</p>
                <p class="mb-2">Clarindo Pereira dos Santos (Movimento dos Pescadores e Pescadoras Artesanais - MPP)</p>
                @include('programacao.local', ['local' => "Auditório Bicalho (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 11 - 15:30 às 17:30</h3>
                <p class="fw-bold mb-3">Botânica Cultural: perspectivas para o futuro das relações entre seres humanos e plantas</p>
                <p class="mb-2">Elaine de Lima de Jesus (UERJ)</p>
                <p class="mb-2">Leomar da Silva de Lima (UEFS)</p>
                <p class="mb-2">Thaís Salatiel de Azevedo (UERJ)</p>
                @include('programacao.local', ['local' => "Auditório Bicalho (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 12 - 15:30 às 17:30</h3>
                <p class="fw-bold mb-3">Pluriversidades: a interculturalidade na formação de pesquisadores socioambientais</p>
                <p class="mb-2">Celso Sánchez Pereira (UNIRIO/GEASur)</p>
                <p class="mb-2">Yanina Micaela Sammarc (UFPR/PPGMADE/GEPE Socioambiental)</p>
                <p class="mb-2">Geilsa Costa Santos-Baptista (UEFS)</p>
                <p class="mb-2">Geilsa Costa Santos-Baptista (UEFS)</p>
                <p class="mb-2">Fabíola Cardoso Vieira (UFMG, Instituto Awêry)</p>
                <p class="mb-2">Norma Constanza Castaño Cuéllar (Universidad Pedagógica Nacional, Colombia)</p>
                @include('programacao.local', ['local' => "Sala 1204 Teleconferências (Faculdade de Educação)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Mesa redonda 13 - 15:30 às 17:30</h3>
                <p class="fw-bold mb-3">Quem produz conhecimento em Etnobiologia? Desigualdades, colonialidade e produção científica</p>
                <p class="mb-2">David Jiménez-Escobar(UNC, Argentina)</p>
                <p class="mb-2">Flávia Rosa Santoro (UENF)</p>
                @include('programacao.local', ['local' => "Auditório Carangola (FAFICH)"])
            </section>
            <h2 class="h3 mt-5 mb-4" style="color: #3d93a9;">Simpósios Especiais</h2>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Simpósio Especial 4 - 13:00 às 17:00</h3>
                <p class="fw-bold mb-3">Práticas tradicionais de cura e cuidado: lutas, visibilidade e descriminalização</p>
                <p class="mb-2">(Entrega da declaração como Patrimônio Cultural do Brasil do Oficio de Raizeiras e Raizeiros do Cerrado pelo IPHAN)</p>
                <p class="mb-2">Daniela Ribeiro de Souza (Pastoral da Saúde, Rede dos Povos e Comunidades Tradicionais do Brasil)</p>
                <p class="mb-2">Jaqueline Evangelista Dias (Articulação Pacari de Plantas Medicinais do Cerrado)</p>
                <p class="mb-2">Juliana Antônia Ferreira Fernandes (Rede dos Povos e Comunidades Tradicionais do Brasil)</p>
                <p class="mb-2">Aparecida Arruda (Ervanário São Francisco de Assis, Sabará/MG)</p>
                <p class="mb-2">Shirlene Sabino (Comunidade Quilombola do Açude, Jaboticatubas/MG)</p>
                <p class="mb-2">Dona Maria dos RemédiosMORRO DO PILAR</p>
                <p class="mb-2">Sabrina Almeida (UFMG)</p>
                <p class="mb-2">Silvia Guimarães (UnB)</p>
                <p class="mb-2">Lucely Pio (Quilombo do Cedro, Goiás, Articulação Pacari - Raizeiras do Cerrado)</p>
                <p class="mb-2">Lourdes Cardozo Laureano (Articulação Pacari de Plantas Medicinais do Cerrado)</p>
                <p class="mb-2">Vivian Camacho (Salud de los Pueblos, Bolívia)</p>
                @include('programacao.local', ['local' => "Auditório do Instituto de Geociências (IGC)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Simpósio Especial 7 - 13:00 às 17:00</h3>
                <p class="fw-bold mb-3">Manejo e conservação da fauna no Brasil: abordagens interdisciplinares e transdisciplinares</p>
                <p class="mb-2">Dídac Santos Fita (UCM, Espanha)</p>
                <p class="mb-2">George Henrique Rebêlo (INPA)</p>
                <p class="mb-2">Juarez Pezzuti (UFPA)</p>
                <p class="mb-2">Anna Karolina Martins Borges (UFRPE)</p>
                <p class="mb-2">Thiago da Silva Novato (USP)</p>
                <p class="mb-2">Yedda Christina Bezerra Barbosa de Oliveira (Instituto Nacional de Ciência Cidadã (INCC))</p>
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Simpósio Especial 8 - 13:00 às 17:00</h3>
                <p class="fw-bold mb-3">Estratégias populares de salvaguarda dos conhecimentos tradicionais, rastreabilidade e repartição de benefícios</p>
                <p class="mb-2">Aparecida Arruda (Ervanário São Francisco de Assis, Sabará/MG)</p>
                <p class="mb-2">Carolina Levis  (UFSC)</p>
                <p class="mb-2">Cristiane Gomes Julião Pankararu (Câmara Setorial das Guardiães e dos Guardiões da Biodiversidade - CSG)</p>
                <p class="mb-2">Edinalda Pereira do Nascimento (Rede de Comunidades Tradicionais Pantaneira)</p>
                <p class="mb-2">Enilza da Silva (Rede de Comunidades Tradicionais Pantaneira)</p>
                <p class="mb-2">Gustavo Taboada Soldati (UFJF)</p>
                <p class="mb-2">Inês Ribeiro de Andrade (Fundação Zôobotânica – BH)</p>
                <p class="mb-2">Jaqueline Evangelista (Articulação Pacari - Raizeiras do Cerrado)</p>
                <p class="mb-2">Lourdes Cardozo Laureano (Articulação Pacari - Raizeiras do Cerrado)</p>
                <p class="mb-2">Lourenço Pereira Leite (Rede de Comunidades Tradicionais Pantaneira)</p>
                <p class="mb-2">Lucely Morais Pio (Articulação Pacari de Plantas Medicinais do Cerrado)</p>
                <p class="mb-2">Marciano Toledo (Câmara Setorial das Guardiães e dos Guardiões da Biodiversidade -CSG)</p>
                <p class="mb-2">Natalia Hanazaki (UFSC)</p>
                <p class="mb-2">Nivaldo Peroni (UFSC)</p>
                <p class="mb-2">Sofia Zank  (CNPT/ICMBio, UFSC)</p>
                <p class="mb-2">Viviane Stern da Fonseca-Kruel (UFRJ, Jardim Botânico do Rio de Janeiro)</p>
                <p class="mb-2">Waldileno Xavier da Silva (Rede de Comunidades Tradicionais Pantaneira)</p>
                @include('programacao.local', ['local' => "Auditório 3 – Cerrado – Instituto de Ciências Biológicas"])
            </section>
            <h2 class="h3 mt-5 mb-4" style="color: #3d93a9;">Palestras</h2>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Palestra 6 - 18:30 às 19:30</h3>
                <p class="fw-bold mb-3">Entre repositórios digitais, bancos de dados e aplicativos de reconhecimento de plantas, quais ferramentas e perspectivas para a coleta, a proteção e a restituição do conhecimento local sobre as plantas?</p>
                <p class="mb-2">Guillaume Odonne (CNRS, Guiana Francesa)</p>
                @include('programacao.local', ['local' => "Auditório 3 – Cerrado – Instituto de Ciências Biológicas"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Palestra 7 - 18:30 às 19:30</h3>
                <p class="fw-bold mb-3">Diálogo inter-ontológico: una propuesta para la defensa, autogestión y manejo de los territórios</p>
                <p class="mb-2">Erendira Juanita Cano Contreras (SOLAE; UAB, Espanha)</p>
                @include('programacao.local', ['local' => "Auditório Bicalho (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Palestra 8 - 18:30 às 19:30</h3>
                <p class="fw-bold mb-3">A pesca artesanal de Cabo Verde frente à máquina antipolítica do desenvolvimento</p>
                <p class="mb-2">João Paulo Araújo Silva (UFMG)</p>
                @include('programacao.local', ['local' => "Auditório Adriana Bogliolo (Escola de Ciência da Informação)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Palestra 9 - 18:30 às 19:30</h3>
                <p class="fw-bold mb-3">Ações de enfretamento à vassoura de bruxa da mandioca nas Terras Indígenas de Oiapoque, Amapá</p>
                <p class="mb-2">Cacique Janina Forte (Iepé – Instituto de Pesquisa e Formação Indígena)</p>
                <p class="mb-2">Adilaudo dos Santos (Iepé – Instituto de Pesquisa e Formação Indígena)</p>
                @include('programacao.local', ['local' => "Auditório Carangola (FAFICH)"])
            </section>
            <section class="shadow-sm p-4 mb-4 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                <h3 class="h4 mb-3" style="color: #3d93a9;">Palestra 10 - 18:30 às 19:30</h3>
                <p class="fw-bold mb-3">Etnobiología Comprometida y sus importancia en el contexto actual de América Latina.</p>
                <p class="mb-2">Álvaro Sepúlveda-Pío (Sociedad Colombiana de Etnobiología)</p>
                @include('programacao.local', ['local' => "Auditório Baesse (FAFICH)"])
            </section>
        </div>
    </div>
</div>
@endsection
