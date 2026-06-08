@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Termos de Uso da Plataforma</h1>

    <div class="bg-white p-4 shadow rounded" style="line-height: 1.6; font-size: 1rem;">
        <ul style="list-style-type: none; padding-left: 0;">
            <li>
                <ul style="list-style-type: none;">
                    <li><h5>1. Introdução</h5></li>
                    <li>Bem-vindo aos termos de uso da Plataforma de inscrições e submissão de trabalhos da Universidade Federal do Agreste de Pernambuco (UFAPE). Estes termos regem o uso do nosso aplicativo web.</li>
                </ul>
            </li>
            <br/>
            <li>
                <ul style="list-style-type: none;">
                    <li><h5>2. Aceitação do Termo de Uso e Política de Privacidade</h5></li>
                    <li>2.1 - Ao utilizar a Plataforma de inscrições e submissão de trabalhos da Universidade Federal do Agreste de Pernambuco (UFAPE), o usuário confirma que leu, compreendeu e que aceita os termos e políticas aplicáveis e fica a eles vinculado.</li>
                    <li>2.2 - Caso não concorde com as regras presentes nestes Termos, o Usuário não poderá acessar e utilizar o sistema e dispor dos seus serviços. </li>
                </ul style="list-style-type: none;">
            </li>
            <br/>
            <li>
                <ul style="list-style-type: none;">
                    <li><h5>3. Legislação Aplicada</h5></li>
                    <li>Elencamos abaixo leis e normativos que você pode consultar para esclarecer dúvidas relacionadas aos serviços que envolvam tratamento dos dados, transparência na administração pública, direito dos titulares, entre outros:</li>
                    <li>3.1 - <a href="https://www.planalto.gov.br/ccivil_03/_ato2015-2018/2018/lei/L13709.htm" target="_blank">Lei nº 13.709, de 14 de agosto de 2018:</a> Lei Geral de Proteção de Dados Pessoais (LGPD);</li>
                    <li>3.2 - <a href="https://www.planalto.gov.br/ccivil_03/_ato2015-2018/2017/lei/l13460.htm" target="_blank">Lei nº 13.460, de 26 de junho de 2017:</a> dispõe sobre participação, proteção e defesa dos direitos do usuário dos serviços públicos da administração pública.</li>
                    <li>3.3 - <a href="https://www.planalto.gov.br/ccivil_03/_ato2011-2014/2014/lei/l12965.htm" target="_blank">Lei nº 12.965, de 23 de abril de 2014 (Marco Civil da Internet):</a> estabelece princípios, garantias, direitos e deveres para o uso da Internet no Brasil.</li>
                    <li>3.4 - <a href="https://www.planalto.gov.br/ccivil_03/_ato2011-2014/2011/lei/l12527.htm" target="_blank">Lei nº 12.527, de 18 de novembro de 2011 (Lei de Acesso à Informação):</a>regula o acesso a informações previsto na Constituição Federal.</li>
                    <li>3.5 - <a href="https://www.planalto.gov.br/ccivil_03/_ato2011-2014/2012/decreto/d7724.htm" target="_blank">Decreto nº 7.724, de 16 de maio de 2012:</a> regulamenta a Lei de Acesso à informação.</li>
                </ul>
            </li>
            <br/>
            <li>
                <ul style="list-style-type: none;">
                    <li><h5>4. Uso do Aplicativo</h5></li>
                    <li>4.1 - Os dados que compõe o sistema são de uso da Universidade Federal do Agreste de Pernambuco e das pró-reitorias que a compõem.</li>
                    <li>4.2 - As informações e os dados do sistema podem ser modificados sem aviso prévio ao usuário.</li>
                </ul>
            </li>
            <br/>
            <li>
                <ul style="list-style-type: none;">
                    <li><h5>5. Direitos do Usuário</h5></li>
                    <li>5.1 - Confirmação e acesso: É o direito de obter a conﬁrmação de quais dados pessoais são ou não objeto de tratamento e, se for esse o caso, o direito de acessar os seus dados pessoais.</li>
                    <li>5.2 - Retificação: É o direito de solicitar a correção de dados incompletos, inexatos ou desatualizados. </li>
                    <li>5.3 - Limitação do tratamento dos dados: É o direito de limitar o tratamento de seus dados pessoais, podendo exigir a eliminação de dados desnecessários, excessivos ou tratados em desconformidade com a Lei Geral de Proteção de Dados.</li>
                    <li>5.4 - Oposição: É o direito de, a qualquer momento, se opor ao tratamento de dados por motivos relacionados com a sua situação particular, em caso de descumprimento ao disposto na Lei Geral de Proteção de Dados.</li>
                </ul>
            </li>
            <br/>
            <li>
                <ul style="list-style-type: none;">
                    <li><h5>6. Deveres do Usuário</h5></li>
                    <li>6.1 - Veracidade das informações: Caso os dados informados não possuam veracidade e precisão, pode ser que não consiga utilizar o serviço. Você, como usuário do serviço, é responsável pela atualização das suas informações pessoais e pelas consequências na omissão ou erros nas informações pessoais cadastradas.</li>
                    <li>6.2 - Não compartilhamento de login e/ou senha: O login e senha só poderão ser utilizados pelo usuário cadastrado. Você deve manter sigilo da senha, que é pessoal e intransferível, não podendo alegar uso indevido, após seu compartilhamento.</li>
                    <li>6.3 - Responsabilidade pelos atos: O usuário é responsável pela reparação de todos e quaisquer danos, diretos ou indiretos (inclusive decorrentes do desrespeito de quaisquer direitos de outros usuários, de terceiros, inclusive direitos de propriedade intelectual, de segredo e de personalidade), que sejam causados à Administração Pública, a qualquer outro usuário, ou, ainda, a qualquer terceiro, inclusive no ato do descumprimento do estabelecido nestes Termos de Uso ou de qualquer ato praticado a partir de seu acesso ao serviço.</li>
                    <li>6.4 - Não interferir, comprometer ou interromper o serviço: Vale também em relação a servidores ou redes conectadas ao serviço, por meio da transmissão de qualquer malware, worm, vírus, spyware ou outro código malicioso. Você não pode inserir conteúdo ou códigos ou, de outra forma, alterar ou interferir na maneira como a página do serviço é exibida ou processada no dispositivo do usuário. Tendo em vista que o serviço lida com informações pessoais, você, como usuário, concorda que não usará robôs, sistemas de varredura e armazenamento de dados (como “spiders” ou “scrapers”), links escondidos ou qualquer outro recurso escuso, ferramenta, programa, algoritmo ou método coletor/extrator de dados automático para acessar, adquirir, copiar ou monitorar o serviço, sem permissão expressa por escrito da Universidade Federal do Agreste de Pernambuco.</li>
                    <li>6.5 - Insenção de responsabilidade da administração pública: A Universidade Federal do Agreste de Pernambuco não poderá ser responsabilizada por: equipamento infetado ou invadido por atacantes, equipamento danificado no momento do consumo dos serviços, proteção do computador, proteção das informações baseadas nos computadores dos usuários, abuso de uso dos computadores dos usuários, monitoração ilegal do computador dos usuários, vulnerabilidades ou instabilidades existentes nos sistemas dos usuários, perímetro inseguro.</li>
                </ul>
            </li>
            <br/>
            <li>
                <ul style="list-style-type: none;">
                    <li><h5>7. Responsabilidade da Administração Pública com os Dados Pessoais</h5></li>
                    <li>7.1 - A Administração Pública se compromete em cumprir todas as legislações relativas ao uso correto dos dados pessoais do cidadão, bem como a garantir todos os direitos e garantias legais dos usuários. Ela também se obriga a promover, independentemente de solicitações, a divulgação em local de fácil acesso, no âmbito de suas competências, de informações de interesse coletivo ou geral produzidas ou custodiadas. É de responsabilidade da Administração Pública implementar controles de segurança para proteção dos dados pessoais dos usuários.</li>
                    <li>7.2 - A Administração Pública poderá, quanto às ordens judiciais de pedido das informações, compartilhar informações necessárias para investigações ou tomar medidas relacionadas a atividades ilegais, suspeitas de fraude ou ameaças potenciais contra pessoas, bens ou sistemas que sustentam o Serviço ou de outra forma necessária para cumprir com nossas obrigações legais. Caso ocorra, a Administração Pública notificará os usuários, salvo quando o processo estiver em segredo de justiça.</li>
                </ul>
            </li>
            <br/>
            <li>
                <ul style="list-style-type: none;">
                    <li><h5>8. Links</h5></li>
                    <li>Este sistema pode conter links para outros sites que não são mantidos ou controlados pela Universidade Federal do Agreste de Pernambuco. A Universidade não possui controle sobre o conteúdo, políticas de privacidade ou práticas de sites de terceiros, não assumindo quaisquer responsabilidades sobre estes.</li>
                </ul>
            </li>
            <br/>
            <li>
                <ul style="list-style-type: none;">
                    <li><h5>9. Alterações dos Termos de Uso</h5></li>
                    <li>A Universidade Federal do Agreste de Pernambuco poderá revisar e atualizar estes termos de uso e de privacidade a qualquer momento, ficando o usuário vinculado à versão atualizada desses termos.</li>
                </ul>
            </li>
            <br/>
            <li>
                <ul style="list-style-type: none;">
                    <li><a href="{{ route('aviso.de.privacidade') }}">Leia também nosso Aviso de Privacidade</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
@endsection
