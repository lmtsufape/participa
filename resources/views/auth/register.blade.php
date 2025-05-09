@extends('layouts.app')

@section('content')
<div class="container content mb-5 position-relative">
    <style>
        .etapas {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #ccc;
            margin-bottom: 20px;
            font-family: sans-serif;
        }
        
        .etapa {
            flex: 1;
            text-align: left;
            padding: 10px 0;
            color: #aaa;
            font-weight: normal;
            border-bottom: 2px solid transparent;
        }
        
        .etapa.ativa {
            color: #004d51;
            font-weight: bold;
            border-bottom: 2px solid #004d51;
        }
    </style>
    
    <br><br>

    <div class="row titulo text-center" style="color: #034652;">
        <h2 style="font-weight: bold;">{{__('Cadastro')}}</h2>
    </div>

    @if(Auth::check())
        <form method="POST" action="{{ route('administrador.criarUsuario', app()->getLocale()) }}">
    @else
        <form method="POST" action="{{ route('register', app()->getLocale())}}">
    @endif
        <div id="etapa-1">
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="pais" class="col-form-label">{{ __('País') }}</label>
                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control @error('pais') is-invalid @enderror" id="pais">
                        <option @if($pais == 'afeganistao') selected @endif value="/pt-BR/register/afeganistao">{{__('Afeganistão')}}</option>
                        <option @if($pais == 'albania') selected @endif value="/pt-BR/register/albania">{{__('Albânia')}}</option>
                        <option @if($pais == 'algeria') selected @endif value="/pt-BR/register/algeria">{{__('Argélia')}}</option>
                        <option @if($pais == 'andorra') selected @endif value="/pt-BR/register/andorra">{{__('Andorra')}}</option>
                        <option @if($pais == 'angola') selected @endif value="/pt-BR/register/angola">{{__('Angola')}}</option>
                        <option @if($pais == 'antigua_barbuda') selected @endif value="/pt-BR/register/antigua_barbuda">{{__('Antígua e Barbuda')}}</option>
                        <option @if($pais == 'argelia') selected @endif value="/pt-BR/register/argelia">{{__('Argélia')}}</option>
                        <option @if($pais == 'argentina') selected @endif value="/pt-BR/register/argentina">{{__('Argentina')}}</option>
                        <option @if($pais == 'armenia') selected @endif value="/pt-BR/register/armenia">{{__('Armênia')}}</option>
                        <option @if($pais == 'australia') selected @endif value="/pt-BR/register/australia">{{__('Austrália')}}</option>
                        <option @if($pais == 'austria') selected @endif value="/pt-BR/register/austria">{{__('Áustria')}}</option>
                        <option @if($pais == 'azerbaijao') selected @endif value="/pt-BR/register/azerbaijao">{{__('Azerbaijão')}}</option>
                        <option @if($pais == 'bahamas') selected @endif value="/pt-BR/register/bahamas">{{__('Bahamas')}}</option>
                        <option @if($pais == 'bahrain') selected @endif value="/pt-BR/register/bahrain">{{__('Bahrein')}}</option>
                        <option @if($pais == 'bangladesh') selected @endif value="/pt-BR/register/bangladesh">{{__('Bangladesh')}}</option>
                        <option @if($pais == 'barbados') selected @endif value="/pt-BR/register/barbados">{{__('Barbados')}}</option>
                        <option @if($pais == 'belgica') selected @endif value="/pt-BR/register/belgica">{{__('Bélgica')}}</option>
                        <option @if($pais == 'belize') selected @endif value="/pt-BR/register/belize">{{__('Belize')}}</option>
                        <option @if($pais == 'benin') selected @endif value="/pt-BR/register/benin">{{__('Benin')}}</option>
                        <option @if($pais == 'bhutao') selected @endif value="/pt-BR/register/bhutao">{{__('Butão')}}</option>
                        <option @if($pais == 'bolivia') selected @endif value="/pt-BR/register/bolivia">{{__('Bolívia')}}</option>
                        <option @if($pais == 'bosnia_herzegovina') selected @endif value="/pt-BR/register/bosnia_herzegovina">{{__('Bósnia e Herzegovina')}}</option>
                        <option @if($pais == 'botswana') selected @endif value="/pt-BR/register/botswana">{{__('Botswana')}}</option>
                        <option @if($pais == 'brasil') selected @endif value="/pt-BR/register/brasil" selected hidden>{{__('Brasil')}}</option>
                        <option @if($pais == 'brunei') selected @endif value="/pt-BR/register/brunei">{{__('Brunei')}}</option>
                        <option @if($pais == 'bulgaria') selected @endif value="/pt-BR/register/bulgaria">{{__('Bulgária')}}</option>
                        <option @if($pais == 'burkina_faso') selected @endif value="/pt-BR/register/burkina_faso">{{__('Burquina Faso')}}</option>
                        <option @if($pais == 'burundi') selected @endif value="/pt-BR/register/burundi">{{__('Burundi')}}</option>
                        <option @if($pais == 'cabo_verde') selected @endif value="/pt-BR/register/cabo_verde">{{__('Cabo Verde')}}</option>
                        <option @if($pais == 'camarões') selected @endif value="/pt-BR/register/camarao">{{__('Camarões')}}</option>
                        <option @if($pais == 'canada') selected @endif value="/pt-BR/register/canada">{{__('Canadá')}}</option>
                        <option @if($pais == 'catari') selected @endif value="/pt-BR/register/catari">{{__('Catar')}}</option>
                        <option @if($pais == 'chade') selected @endif value="/pt-BR/register/chade">{{__('Chade')}}</option>
                        <option @if($pais == 'chile') selected @endif value="/pt-BR/register/chile">{{__('Chile')}}</option>
                        <option @if($pais == 'china') selected @endif value="/pt-BR/register/china">{{__('China')}}</option>
                        <option @if($pais == 'chipre') selected @endif value="/pt-BR/register/chipre">{{__('Chipre')}}</option>
                        <option @if($pais == 'colombia') selected @endif value="/pt-BR/register/colombia">{{__('Colômbia')}}</option>
                        <option @if($pais == 'comores') selected @endif value="/pt-BR/register/comores">{{__('Comores')}}</option>
                        <option @if($pais == 'congo') selected @endif value="/pt-BR/register/congo">{{__('Congo')}}</option>
                        <option @if($pais == 'coreia_do_norte') selected @endif value="/pt-BR/register/coreia_do_norte">{{__('Coreia do Norte')}}</option>
                        <option @if($pais == 'coreia_do_sul') selected @endif value="/pt-BR/register/coreia_do_sul">{{__('Coreia do Sul')}}</option>
                        <option @if($pais == 'croacia') selected @endif value="/pt-BR/register/croacia">{{__('Croácia')}}</option>
                        <option @if($pais == 'cuba') selected @endif value="/pt-BR/register/cuba">{{__('Cuba')}}</option>
                        <option @if($pais == 'dinamarca') selected @endif value="/pt-BR/register/dinamarca">{{__('Dinamarca')}}</option>
                        <option @if($pais == 'dominica') selected @endif value="/pt-BR/register/dominica">{{__('Dominica')}}</option>
                        <option @if($pais == 'egito') selected @endif value="/pt-BR/register/egito">{{__('Egito')}}</option>
                        <option @if($pais == 'el_salvador') selected @endif value="/pt-BR/register/el_salvador">{{__('El Salvador')}}</option>
                        <option @if($pais == 'embudos') selected @endif value="/pt-BR/register/embudos">{{__('Embudos')}}</option>
                        <option @if($pais == 'emirados_arabes_unidos') selected @endif value="/pt-BR/register/emirados_arabes_unidos">{{__('Emirados Árabes Unidos')}}</option>
                        <option @if($pais == 'equador') selected @endif value="/pt-BR/register/equador">{{__('Equador')}}</option>
                        <option @if($pais == 'eritrea') selected @endif value="/pt-BR/register/eritrea">{{__('Eritreia')}}</option>
                        <option @if($pais == 'eslovaquia') selected @endif value="/pt-BR/register/eslovaquia">{{__('Eslováquia')}}</option>
                        <option @if($pais == 'eslovenia') selected @endif value="/pt-BR/register/eslovenia">{{__('Eslovênia')}}</option>
                        <option @if($pais == 'espanha') selected @endif value="/pt-BR/register/espanha">{{__('Espanha')}}</option>
                        <option @if($pais == 'estados_unidos') selected @endif value="/pt-BR/register/estados_unidos">{{__('Estados Unidos da América')}}</option>
                        <option @if($pais == 'estonia') selected @endif value="/pt-BR/register/estonia">{{__('Estônia')}}</option>
                        <option @if($pais == 'etiópia') selected @endif value="/pt-BR/register/etopia">{{__('Etiópia')}}</option>
                        <option @if($pais == 'fiji') selected @endif value="/pt-BR/register/fiji">{{__('Fiji')}}</option>
                        <option @if($pais == 'filipinas') selected @endif value="/pt-BR/register/filipinas">{{__('Filipinas')}}</option>
                        <option @if($pais == 'finlandia') selected @endif value="/pt-BR/register/finlandia">{{__('Finlândia')}}</option>
                        <option @if($pais == 'franca') selected @endif value="/pt-BR/register/franca">{{__('França')}}</option>
                        <option @if($pais == 'gabon') selected @endif value="/pt-BR/register/gabon">{{__('Gabão')}}</option>
                        <option @if($pais == 'gambia') selected @endif value="/pt-BR/register/gambia">{{__('Gâmbia')}}</option>
                        <option @if($pais == 'georgia') selected @endif value="/pt-BR/register/georgia">{{__('Geórgia')}}</option>
                        <option @if($pais == 'gibraltar') selected @endif value="/pt-BR/register/gibraltar">{{__('Gibraltar')}}</option>
                        <option @if($pais == 'granada') selected @endif value="/pt-BR/register/granada">{{__('Granada')}}</option>
                        <option @if($pais == 'greece') selected @endif value="/pt-BR/register/greece">{{__('Grécia')}}</option>
                        <option @if($pais == 'guatemala') selected @endif value="/pt-BR/register/guatemala">{{__('Guatemala')}}</option>
                        <option @if($pais == 'guinea') selected @endif value="/pt-BR/register/guinea">{{__('Guiné')}}</option>
                        <option @if($pais == 'guine_bissau') selected @endif value="/pt-BR/register/guine_bissau">{{__('Guiné-Bissau')}}</option>
                        <option @if($pais == 'guyana') selected @endif value="/pt-BR/register/guyana">{{__('Guiana')}}</option>
                        <option @if($pais == 'haiti') selected @endif value="/pt-BR/register/haiti">{{__('Haiti')}}</option>
                        <option @if($pais == 'honduras') selected @endif value="/pt-BR/register/honduras">{{__('Honduras')}}</option>
                        <option @if($pais == 'hong_kong') selected @endif value="/pt-BR/register/hong_kong">{{__('Hong Kong')}}</option>
                        <option @if($pais == 'hungria') selected @endif value="/pt-BR/register/hungria">{{__('Hungria')}}</option>
                        <option @if($pais == 'iacos') selected @endif value="/pt-BR/register/iacos">{{__('Iacós')}}</option>
                        <option @if($pais == 'islândia') selected @endif value="/pt-BR/register/islandia">{{__('Islândia')}}</option>
                        <option @if($pais == 'india') selected @endif value="/pt-BR/register/india">{{__('Índia')}}</option>
                        <option @if($pais == 'indonesia') selected @endif value="/pt-BR/register/indonesia">{{__('Indonésia')}}</option>
                        <option @if($pais == 'irlanda') selected @endif value="/pt-BR/register/irlanda">{{__('Irlanda')}}</option>
                        <option @if($pais == 'irã') selected @endif value="/pt-BR/register/ira">{{__('Irã')}}</option>
                        <option @if($pais == 'iraque') selected @endif value="/pt-BR/register/iraque">{{__('Iraque')}}</option>
                        <option @if($pais == 'israel') selected @endif value="/pt-BR/register/israel">{{__('Israel')}}</option>
                        <option @if($pais == 'italia') selected @endif value="/pt-BR/register/italia">{{__('Itália')}}</option>
                        <option @if($pais == 'jamaica') selected @endif value="/pt-BR/register/jamaica">{{__('Jamaica')}}</option>
                        <option @if($pais == 'japão') selected @endif value="/pt-BR/register/japao">{{__('Japão')}}</option>
                        <option @if($pais == 'jordania') selected @endif value="/pt-BR/register/jordania">{{__('Jordânia')}}</option>
                        <option @if($pais == 'juliano') selected @endif value="/pt-BR/register/juliano">{{__('Juliano')}}</option>
                        <option @if($pais == 'laos') selected @endif value="/pt-BR/register/laos">{{__('Laos')}}</option>
                        <option @if($pais == 'lesoto') selected @endif value="/pt-BR/register/lesoto">{{__('Lesoto')}}</option>
                        <option @if($pais == 'letonia') selected @endif value="/pt-BR/register/letonia">{{__('Letônia')}}</option>
                        <option @if($pais == 'libano') selected @endif value="/pt-BR/register/libano">{{__('Líbano')}}</option>
                        <option @if($pais == 'liberia') selected @endif value="/pt-BR/register/liberia">{{__('Libéria')}}</option>
                        <option @if($pais == 'liechtenstein') selected @endif value="/pt-BR/register/liechtenstein">{{__('Liechtenstein')}}</option>
                        <option @if($pais == 'lituania') selected @endif value="/pt-BR/register/lituania">{{__('Lituânia')}}</option>
                        <option @if($pais == 'luxemburgo') selected @endif value="/pt-BR/register/luxemburgo">{{__('Luxemburgo')}}</option>
                        <option @if($pais == 'macedonia') selected @endif value="/pt-BR/register/macedonia">{{__('Macedônia')}}</option>
                        <option @if($pais == 'madagascar') selected @endif value="/pt-BR/register/madagascar">{{__('Madagáscar')}}</option>
                        <option @if($pais == 'malasia') selected @endif value="/pt-BR/register/malasia">{{__('Malásia')}}</option>
                        <option @if($pais == 'malaui') selected @endif value="/pt-BR/register/malaui">{{__('Malawi')}}</option>
                        <option @if($pais == 'maldivas') selected @endif value="/pt-BR/register/maldivas">{{__('Maldivas')}}</option>
                        <option @if($pais == 'mali') selected @endif value="/pt-BR/register/mali">{{__('Mali')}}</option>
                        <option @if($pais == 'malta') selected @endif value="/pt-BR/register/malta">{{__('Malta')}}</option>
                        <option @if($pais == 'marianas') selected @endif value="/pt-BR/register/marianas">{{__('Marianas')}}</option>
                        <option @if($pais == 'marrocos') selected @endif value="/pt-BR/register/marrocos">{{__('Marrocos')}}</option>
                        <option @if($pais == 'martinica') selected @endif value="/pt-BR/register/martinica">{{__('Martinica')}}</option>
                        <option @if($pais == 'mauricio') selected @endif value="/pt-BR/register/mauricio">{{__('Maurício')}}</option>
                        <option @if($pais == 'mauritania') selected @endif value="/pt-BR/register/mauritania">{{__('Mauritânia')}}</option>
                        <option @if($pais == 'mexico') selected @endif value="/pt-BR/register/mexico">{{__('México')}}</option>
                        <option @if($pais == 'micronesia') selected @endif value="/pt-BR/register/micronesia">{{__('Micronésia')}}</option>
                        <option @if($pais == 'moçambique') selected @endif value="/pt-BR/register/mocambique">{{__('Moçambique')}}</option>
                        <option @if($pais == 'moldavia') selected @endif value="/pt-BR/register/moldavia">{{__('Moldávia')}}</option>
                        <option @if($pais == 'monaco') selected @endif value="/pt-BR/register/monaco">{{__('Mônaco')}}</option>
                        <option @if($pais == 'mongolia') selected @endif value="/pt-BR/register/mongolia">{{__('Mongólia')}}</option>
                        <option @if($pais == 'montenegro') selected @endif value="/pt-BR/register/montenegro">{{__('Montenegro')}}</option>
                        <option @if($pais == 'namibia') selected @endif value="/pt-BR/register/namibia">{{__('Namíbia')}}</option>
                        <option @if($pais == 'nauru') selected @endif value="/pt-BR/register/nauru">{{__('Nauru')}}</option>
                        <option @if($pais == 'nepal') selected @endif value="/pt-BR/register/nepal">{{__('Nepal')}}</option>
                        <option @if($pais == 'nicaragua') selected @endif value="/pt-BR/register/nicaragua">{{__('Nicarágua')}}</option>
                        <option @if($pais == 'niger') selected @endif value="/pt-BR/register/niger">{{__('Níger')}}</option>
                        <option @if($pais == 'nigeria') selected @endif value="/pt-BR/register/nigeria">{{__('Nigéria')}}</option>
                        <option @if($pais == 'niue') selected @endif value="/pt-BR/register/niue">{{__('Niue')}}</option>
                        <option @if($pais == 'nova_zelandia') selected @endif value="/pt-BR/register/nova_zelandia">{{__('Nova Zelândia')}}</option>
                        <option @if($pais == 'nicaragua') selected @endif value="/pt-BR/register/nicaragua">{{__('Nicarágua')}}</option>
                        <option @if($pais == 'noruega') selected @endif value="/pt-BR/register/noruega">{{__('Noruega')}}</option>
                        <option @if($pais == 'nova_zelandia') selected @endif value="/pt-BR/register/nova_zelandia">{{__('Nova Zelândia')}}</option>
                        <option @if($pais == 'panama') selected @endif value="/pt-BR/register/panama">{{__('Panamá')}}</option>
                        <option @if($pais == 'papua_nova_guinea') selected @endif value="/pt-BR/register/papua_nova_guinea">{{__('Papua Nova Guiné')}}</option>
                        <option @if($pais == 'paquistao') selected @endif value="/pt-BR/register/paquistao">{{__('Paquistão')}}</option>
                        <option @if($pais == 'paraguai') selected @endif value="/pt-BR/register/paraguai">{{__('Paraguai')}}</option>
                        <option @if($pais == 'peru') selected @endif value="/pt-BR/register/peru">{{__('Peru')}}</option>
                        <option @if($pais == 'polonia') selected @endif value="/pt-BR/register/polonia">{{__('Polônia')}}</option>
                        <option @if($pais == 'portugal') selected @endif value="/pt-BR/register/portugal">{{__('Portugal')}}</option>
                        <option @if($pais == 'quenia') selected @endif value="/pt-BR/register/kenia">{{__('Quênia')}}</option>
                        <option @if($pais == 'quiribati') selected @endif value="/pt-BR/register/quiribati">{{__('Quiribati')}}</option>
                        <option @if($pais == 'reino_unido') selected @endif value="/pt-BR/register/reino_unido">{{__('Reino Unido')}}</option>
                        <option @if($pais == 'república_dominicana') selected @endif value="/pt-BR/register/republica_dominicana">{{__('República Dominicana')}}</option>
                        <option @if($pais == 'república_checa') selected @endif value="/pt-BR/register/republica_checa">{{__('República Checa')}}</option>
                        <option @if($pais == 'ruanda') selected @endif value="/pt-BR/register/ruanda">{{__('Ruanda')}}</option>
                        <option @if($pais == 'romênia') selected @endif value="/pt-BR/register/romenia">{{__('Romênia')}}</option>
                        <option @if($pais == 'rússia') selected @endif value="/pt-BR/register/russia">{{__('Rússia')}}</option>
                        <option @if($pais == 'saint_kitts_nevis') selected @endif value="/pt-BR/register/saint_kitts_nevis">{{__('Saint Kitts e Nevis')}}</option>
                        <option @if($pais == 'saint_lucia') selected @endif value="/pt-BR/register/saint_lucia">{{__('Saint Lucia')}}</option>
                        <option @if($pais == 'samoa') selected @endif value="/pt-BR/register/samoa">{{__('Samoa')}}</option>
                        <option @if($pais == 'san_marino') selected @endif value="/pt-BR/register/san_marino">{{__('San Marino')}}</option>
                        <option @if($pais == 'santa_lucia') selected @endif value="/pt-BR/register/santa_lucia">{{__('Santa Lúcia')}}</option>
                        <option @if($pais == 'senegal') selected @endif value="/pt-BR/register/senegal">{{__('Senegal')}}</option>
                        <option @if($pais == 'serra_leoa') selected @endif value="/pt-BR/register/serra_leoa">{{__('Serra Leoa')}}</option>
                        <option @if($pais == 'seychelles') selected @endif value="/pt-BR/register/seychelles">{{__('Seicheles')}}</option>
                        <option @if($pais == 'singapura') selected @endif value="/pt-BR/register/singapura">{{__('Singapura')}}</option>
                        <option @if($pais == 'siria') selected @endif value="/pt-BR/register/siria">{{__('Síria')}}</option>
                        <option @if($pais == 'somalia') selected @endif value="/pt-BR/register/somalia">{{__('Somália')}}</option>
                        <option @if($pais == 'sri_lanka') selected @endif value="/pt-BR/register/sri_lanka">{{__('Sri Lanka')}}</option>
                        <option @if($pais == 'sudan') selected @endif value="/pt-BR/register/sudan">{{__('Sudão')}}</option>
                        <option @if($pais == 'suriname') selected @endif value="/pt-BR/register/suriname">{{__('Suriname')}}</option>
                        <option @if($pais == 'suécia') selected @endif value="/pt-BR/register/suecia">{{__('Suécia')}}</option>
                        <option @if($pais == 'suíça') selected @endif value="/pt-BR/register/suica">{{__('Suíça')}}</option>
                        <option @if($pais == 'sri_lanka') selected @endif value="/pt-BR/register/sri_lanka">{{__('Sri Lanka')}}</option>
                        <option @if($pais == 'tailandia') selected @endif value="/pt-BR/register/tailandia">{{__('Tailândia')}}</option>
                        <option @if($pais == 'taishe') selected @endif value="/pt-BR/register/taishe">{{__('Taishe')}}</option>
                        <option @if($pais == 'taiwan') selected @endif value="/pt-BR/register/taiwan">{{__('Taiwan')}}</option>
                        <option @if($pais == 'tanzânia') selected @endif value="/pt-BR/register/tanzania">{{__('Tanzânia')}}</option>
                        <option @if($pais == 'timor_leste') selected @endif value="/pt-BR/register/timor_leste">{{__('Timor Leste')}}</option>
                        <option @if($pais == 'togo') selected @endif value="/pt-BR/register/togo">{{__('Togo')}}</option>
                        <option @if($pais == 'tonga') selected @endif value="/pt-BR/register/tonga">{{__('Tonga')}}</option>
                        <option @if($pais == 'trinidad_tobago') selected @endif value="/pt-BR/register/trinidad_tobago">{{__('Trinidad e Tobago')}}</option>
                        <option @if($pais == 'tunisia') selected @endif value="/pt-BR/register/tunisia">{{__('Tunísia')}}</option>
                        <option @if($pais == 'turcomenistão') selected @endif value="/pt-BR/register/turcomenistao">{{__('Turcomenistão')}}</option>
                        <option @if($pais == 'turquia') selected @endif value="/pt-BR/register/turquia">{{__('Turquia')}}</option>
                        <option @if($pais == 'tuvalu') selected @endif value="/pt-BR/register/tuvalu">{{__('Tuvalu')}}</option>
                        <option @if($pais == 'uganda') selected @endif value="/pt-BR/register/uganda">{{__('Uganda')}}</option>
                        <option @if($pais == 'uruguai') selected @endif value="/pt-BR/register/uruguai">{{__('Uruguai')}}</option>
                        <option @if($pais == 'uzbequistão') selected @endif value="/pt-BR/register/uzbequistao">{{__('Uzbequistão')}}</option>
                        <option @if($pais == 'vanuatu') selected @endif value="/pt-BR/register/vanuatu">{{__('Vanuatu')}}</option>
                        <option @if($pais == 'vaticano') selected @endif value="/pt-BR/register/vaticano">{{__('Vaticano')}}</option>
                        <option @if($pais == 'venezuela') selected @endif value="/pt-BR/register/venezuela">{{__('Venezuela')}}</option>
                        <option @if($pais == 'vietnam') selected @endif value="/pt-BR/register/vietname">{{__('Vietnã')}}</option>
                        <option @if($pais == 'wallis_futuna') selected @endif value="/pt-BR/register/wallis_futuna">{{__('Wallis e Futuna')}}</option>
                        <option @if($pais == 'zambia') selected @endif value="/pt-BR/register/zambia">{{__('Zâmbia')}}</option>
                        <option @if($pais == 'zimbabue') selected @endif value="/pt-BR/register/zimbabue">{{__('Zimbábue')}}</option>
                    </select>
                    <input type="hidden" name="pais" value="{{$pais}}">

                    <small>{{__('O formulário seguirá os padrões desse país')}}.</small>

                    @error('pais')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <br>

            <div class="etapas" style="font-weight: 500;">
                <div class="etapa ativa">
                    <p>1. Informações pessoais</p>
                </div>
                <div class="etapa">
                    <p>2. Endereço</p>
                </div>
            </div>

            @csrf
            {{-- Nome | CPF --}}
            <div class="container card">
                <br>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="name" class="col-form-label">{{ __('Nome completo') }}</label>
                        <input id="name" type="text" class="form-control apenasLetras @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"  autocomplete="name" autofocus>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="custom-control custom-radio custom-control-inline col-form-label">
                            <input type="radio" id="customRadioInline1" name="customRadioInline" class="custom-control-input" checked>
                            <label class="custom-control-label me-2" for="customRadioInline1">CPF</label>

                            <input type="radio" @error('passaporte') checked @enderror id="customRadioInline2" name="customRadioInline" class="custom-control-input">
                            <label class="custom-control-label me-2" for="customRadioInline2">{{__('CNPJ')}}</label>

                            <input type="radio" @error('passaporte') checked @enderror id="customRadioInline3" name="customRadioInline" class="custom-control-input">
                            <label class="custom-control-label " for="customRadioInline3">{{__('Passaporte')}}</label>
                        </div>

                        <div id="fieldCPF" @error('passaporte') style="display: none" @enderror>
                            <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" value="{{ old('cpf') }}" autocomplete="cpf" placeholder="CPF" autofocus>

                            @error('cpf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div id="fieldCNPJ" @error('passaporte') style="display: block" @enderror style="display: none" >
                            <input id="cnpj" type="text" class="form-control @error('cnpj') is-invalid @enderror" name="cnpj" placeholder="{{__('CNPJ')}}" value="{{ old('cnpj') }}"  autocomplete="cnpj" autofocus>

                            @error('cnpj')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div id="fieldPassaporte" @error('passaporte') style="display: block" @enderror style="display: none" >
                            <input id="passaporte" type="text" class="form-control @error('passaporte') is-invalid @enderror" name="passaporte" placeholder="{{__('Passaporte')}}" value="{{ old('passaporte') }}"  autocomplete="passaporte" autofocus>

                            @error('passaporte')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="instituicao" class="col-form-label">{{ __('Instituição') }}</label>
                        <input id="instituicao" type="text" class="form-control apenasLetras @error('instituicao') is-invalid @enderror" name="instituicao" value="{{ old('instituicao') }}"  autocomplete="instituicao" autofocus>

                        @error('instituicao')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- Instituição de Ensino e Celular --}}
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="celular" class="col-form-label">{{ __('Celular') }}</label><br>
                        <input id="phone" class="form-control celular @error('celular') is-invalid @enderror" type="tel" name="celular" value="{{old('celular')}}" required autocomplete="celular" onkeyup="process(event)">
                        <div class="alert alert-info mt-1" style="display: none"></div>
                        <div id="celular-invalido" class="alert alert-danger mt-1" role="alert"   style="display: none"></div>

                        @error('celular')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="col-form-label">{{ __('E-mail') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- Email | Senha | Confirmar Senha --}}
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="password" class="col-form-label">{{ __('Senha') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password">
                        <small>{{__('Deve ter no mínimo 8 caracteres (letras ou números)')}}.</small>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password-confirm" class="col-form-label">{{ __('Confirmar senha') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-10"></div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary w-100" style="background-color: #034652; color: white; border-color: #034652;" onclick="proximaEtapa()">
                            {{ __('Continuar') }}
                        </button>
                    </div>
                </div>

                <br>
            </div>
        </div>

        <div id="etapa-2" style="display: none;">
            <div class="etapas">
                <div class="etapa">
                    <p>1. Informações pessoais</p>
                </div>
                <div class="etapa ativa">
                    <p>2. Endereço</p>
                </div>
            </div>

            <div class="container card" style="font-weight: 500;">
                <br>
                {{-- Rua | Número | Bairro --}}
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="cep" class="col-form-label">{{ __('CEP') }}@if($pais != 'outro') @endif</label>
                        <input value="{{old('cep')}}" id="cep" type="text"  autocomplete="cep" name="cep" autofocus class="form-control field__input a-field__input" placeholder="{{__('CEP')}}" size="10" maxlength="9" @if($pais != 'outro') required @endif >
                        @error('cep')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="rua" class="col-form-label">{{ __('Rua') }}</label>
                        <input value="{{old('rua')}}" id="rua" type="text" class="form-control @error('rua') is-invalid @enderror" name="rua"  autocomplete="new-password" required>

                        @error('rua')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="numero" class="col-form-label">{{ __('Número') }}@if($pais != 'outro') @endif</label>
                        <input value="{{old('numero')}}" id="numero" type="text" class="form-control @error('numero') is-invalid @enderror" name="numero" autocomplete="numero" maxlength="10" @if($pais != 'outro') required @endif>

                        @error('numero')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="bairro" class="col-form-label">{{ __('Bairro') }}</label>
                        <input value="{{old('bairro')}}" id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro"  autocomplete="bairro" required>

                        @error('bairro')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                        <br>
                    </div>

                    <div class="col-md-4">
                        <label for="complemento" class="col-form-label">{{ __('Complemento') }}</label>
                        <input type="text" value="{{old('complemento')}}" id="complemento" class="form-control  @error('complemento') is-invalid @enderror" name="complemento" >

                        @error('complemento')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="cidade" class="col-form-label">{{ __('Cidade') }}*</label>
                        <input value="{{old('cidade')}}" id="cidade" type="text" class="form-control apenasLetras @error('cidade') is-invalid @enderror" name="cidade"  autocomplete="cidade" required>

                        @error('cidade')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>

                    @if($pais == 'brasil')
                        <!-- <div class="col-sm-6" id="groupformufinput">
                            <label for="ufInput" class="col-form-label">{{ __('UF') }}*</label>
                            <input type="text" value="{{old('uf')}}" id="ufInput" class="form-control  @error('uf') is-invalid @enderror" name="uf" required>

                            @error('uf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div> -->

                        <div class="col-sm-6" id="groupformuf">
                            <label for="uf" class="col-form-label">{{ __('Estado') }}*</label>
                            {{-- <input id="uf" type="text" class="form-control @error('uf') is-invalid @enderror" name="uf" value="{{ old('uf') }}"  autocomplete="uf" autofocus> --}}
                            <select class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf" required>
                                <option value="" disabled selected hidden>{{__()}}</option>
                                <option @if(old('uf') == 'AC') selected @endif value="AC">Acre</option>
                                <option @if(old('uf') == 'AL') selected @endif value="AL">Alagoas</option>
                                <option @if(old('uf') == 'AP') selected @endif value="AP">Amapá</option>
                                <option @if(old('uf') == 'AM') selected @endif value="AM">Amazonas</option>
                                <option @if(old('uf') == 'BA') selected @endif value="BA">Bahia</option>
                                <option @if(old('uf') == 'CE') selected @endif value="CE">Ceará</option>
                                <option @if(old('uf') == 'DF') selected @endif value="DF">Distrito Federal</option>
                                <option @if(old('uf') == 'ES') selected @endif value="ES">Espírito Santo</option>
                                <option @if(old('uf') == 'GO') selected @endif value="GO">Goiás</option>
                                <option @if(old('uf') == 'MA') selected @endif value="MA">Maranhão</option>
                                <option @if(old('uf') == 'MT') selected @endif value="MT">Mato Grosso</option>
                                <option @if(old('uf') == 'MS') selected @endif value="MS">Mato Grosso do Sul</option>
                                <option @if(old('uf') == 'MG') selected @endif value="MG">Minas Gerais</option>
                                <option @if(old('uf') == 'PA') selected @endif value="PA">Pará</option>
                                <option @if(old('uf') == 'PB') selected @endif value="PB">Paraíba</option>
                                <option @if(old('uf') == 'PR') selected @endif value="PR">Paraná</option>
                                <option @if(old('uf') == 'PE') selected @endif value="PE">Pernambuco</option>
                                <option @if(old('uf') == 'PI') selected @endif value="PI">Piauí</option>
                                <option @if(old('uf') == 'RJ') selected @endif value="RJ">Rio de Janeiro</option>
                                <option @if(old('uf') == 'RN') selected @endif value="RN">Rio Grande do Norte</option>
                                <option @if(old('uf') == 'RS') selected @endif value="RS">Rio Grande do Sul</option>
                                <option @if(old('uf') == 'RO') selected @endif value="RO">Rondônia</option>
                                <option @if(old('uf') == 'RR') selected @endif value="RR">Roraima</option>
                                <option @if(old('uf') == 'SC') selected @endif value="SC">Santa Catarina</option>
                                <option @if(old('uf') == 'SP') selected @endif value="SP">São Paulo</option>
                                <option @if(old('uf') == 'SE') selected @endif value="SE">Sergipe</option>
                                <option @if(old('uf') == 'TO') selected @endif value="TO">Tocantins</option>
                            </select>

                            @error('uf')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                            @enderror
                        </div>
                    @else
                        <div class="col-md-6" id="etapa-2" style="display: none;">
                            <label for="uf" class="col-form-label">{{ __('Estado/Província/Região') }}</label>
                            <input type="text" value="{{old('uf')}}" id="uf" class="form-control  @error('uf') is-invalid @enderror" name="uf" >

                            @error('uf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                    @endif
                </div>

                <br>

                <div class="row form-group">
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary w-100" style="background-color: #034652; color: white; border-color: #034652;" onclick="etapaAnterior()">
                            {{ __('Voltar') }}
                        </button>
                    </div>
                    <div class="col-md-8"></div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100" style="background-color: #034652; color: white; border-color: #034652;">
                            {{ __('Confirmar Cadastro') }}
                        </button>
                    </div>
                </div>

                <br><br>
            </div>
        </div>
    </form>
</div>



@endsection

@section('javascript')
  <script type="text/javascript" >
    $(document).ready(function($){

      $('#cpf').mask('000.000.000-00');
      $('#cnpj').mask('00.000.000/0000-00');
      if($('html').attr('lang') == 'en') {
      } else if ($('html').attr('lang') == 'pt-BR') {
        $('#cep').blur(function () {
            pesquisacep(this.value);
        });
        var SPMaskBehavior = function (val) {
          return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
          onKeyPress: function(val, e, field, options) {
            field.mask(SPMaskBehavior.apply({}, arguments), options);
          }
        };
        //$('#celular').mask(SPMaskBehavior, spOptions);
        $('#cep').mask('00000-000');
      }
      $(".apenasLetras").mask("#", {
        maxlength: false,
        translation: {
            '#': {pattern: /[A-zÀ-ÿ ]/, recursive: true}
        }
      });
      //$('#numero').mask('0000000000000');

    });
    function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('rua').value=("");
            document.getElementById('bairro').value=("");
            document.getElementById('cidade').value=("");
            document.getElementById('uf').value=("");
    }

    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('rua').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('uf').value=(conteudo.uf);

        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }

    function pesquisacep(valor) {

        //Nova variável "cep" somente com dígitos.
        var cep = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('rua').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";
                document.getElementById('uf').value="...";


                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    };
  </script>
  <script src="{{ asset('js/celular.js') }}" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
  <script type="text/javascript">

    $(document).ready(function(){
        // $("#fieldPassaporte").hide();
        $("#customRadioInline1").click(function(){
            $("#fieldPassaporte").hide();
            $("#fieldCNPJ").hide();
            $("#fieldCPF").show();
        });

        $("#customRadioInline2").click(function(){
            $("#fieldPassaporte").hide();
            $("#fieldCNPJ").show();
            $("#fieldCPF").hide();
        });

        $("#customRadioInline3").click(function(){
            $("#fieldPassaporte").show();
            $("#fieldCNPJ").hide();
            $("#fieldCPF").hide();
        });

    });

    function proximaEtapa() {
        document.getElementById('etapa-1').style.display = 'none';
        document.getElementById('etapa-2').style.display = 'block';
    }

    function etapaAnterior() {
        document.getElementById('etapa-1').style.display = 'block';
        document.getElementById('etapa-2').style.display = 'none';
    }
    
    $(document).ready(function() {
        $('#pais').select2({
            placeholder: '-- {{__('País')}} --',
            allowClear: true
        });
    });

  </script>

@endsection