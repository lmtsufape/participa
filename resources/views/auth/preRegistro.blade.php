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

        .required-field::after {
            content: "*";
            color: #D44100;
            margin-left: 2px;
        }
    </style>
    
    <br><br>

    @if(session('sucesso'))
        <div class="alert alert-success">
            {{ session('sucesso') }}
        </div>
    @endif

    @if(session('erro'))
        <div class="alert alert-danger">
            {{ session('erro') }}
        </div>
    @endif

    <div class="row titulo text-center" style="color: #034652;">
        <h2 style="font-weight: bold;">{{__('Cadastro')}}</h2>
    </div>

    @php
        $pais = 'brasil';
    @endphp

    @if(Auth::check())
        <form method="POST" action="{{ route('administrador.criarUsuario', app()->getLocale()) }}">
    @else
        <form method="POST" action="{{ route('enviarCodigo') }}">
    @endif
            @csrf

            <div class="form-group row my-3">
                <div class="col-md-12">
                    <label for="pais" class="col-form-label">{{ __('País') }}</label>
                    <select class="form-control @error('pais') is-invalid @enderror" id="pais" name="pais">
                        <option @if($pais == 'afeganistao') selected @endif value="afeganistao">{{__('Afeganistão')}}</option>
                        <option @if($pais == 'albania') selected @endif value="albania">{{__('Albânia')}}</option>
                        <option @if($pais == 'algeria') selected @endif value="algeria">{{__('Argélia')}}</option>
                        <option @if($pais == 'andorra') selected @endif value="andorra">{{__('Andorra')}}</option>
                        <option @if($pais == 'angola') selected @endif value="angola">{{__('Angola')}}</option>
                        <option @if($pais == 'antigua_barbuda') selected @endif value="antigua_barbuda">{{__('Antígua e Barbuda')}}</option>
                        <option @if($pais == 'argelia') selected @endif value="argelia">{{__('Argélia')}}</option>
                        <option @if($pais == 'argentina') selected @endif value="argentina">{{__('Argentina')}}</option>
                        <option @if($pais == 'armenia') selected @endif value="armenia">{{__('Armênia')}}</option>
                        <option @if($pais == 'australia') selected @endif value="australia">{{__('Austrália')}}</option>
                        <option @if($pais == 'austria') selected @endif value="austria">{{__('Áustria')}}</option>
                        <option @if($pais == 'azerbaijao') selected @endif value="azerbaijao">{{__('Azerbaijão')}}</option>
                        <option @if($pais == 'bahamas') selected @endif value="bahamas">{{__('Bahamas')}}</option>
                        <option @if($pais == 'bahrain') selected @endif value="bahrain">{{__('Bahrein')}}</option>
                        <option @if($pais == 'bangladesh') selected @endif value="bangladesh">{{__('Bangladesh')}}</option>
                        <option @if($pais == 'barbados') selected @endif value="barbados">{{__('Barbados')}}</option>
                        <option @if($pais == 'belgica') selected @endif value="belgica">{{__('Bélgica')}}</option>
                        <option @if($pais == 'belize') selected @endif value="belize">{{__('Belize')}}</option>
                        <option @if($pais == 'benin') selected @endif value="benin">{{__('Benin')}}</option>
                        <option @if($pais == 'bhutao') selected @endif value="bhutao">{{__('Butão')}}</option>
                        <option @if($pais == 'bolivia') selected @endif value="bolivia">{{__('Bolívia')}}</option>
                        <option @if($pais == 'bosnia_herzegovina') selected @endif value="bosnia_herzegovina">{{__('Bósnia e Herzegovina')}}</option>
                        <option @if($pais == 'botswana') selected @endif value="botswana">{{__('Botswana')}}</option>
                        <option @if($pais == 'brasil') selected @endif value="brasil">{{__('Brasil')}}</option>
                        <option @if($pais == 'brunei') selected @endif value="brunei">{{__('Brunei')}}</option>
                        <option @if($pais == 'bulgaria') selected @endif value="bulgaria">{{__('Bulgária')}}</option>
                        <option @if($pais == 'burkina_faso') selected @endif value="burkina_faso">{{__('Burquina Faso')}}</option>
                        <option @if($pais == 'burundi') selected @endif value="burundi">{{__('Burundi')}}</option>
                        <option @if($pais == 'cabo_verde') selected @endif value="cabo_verde">{{__('Cabo Verde')}}</option>
                        <option @if($pais == 'camarões') selected @endif value="camarao">{{__('Camarões')}}</option>
                        <option @if($pais == 'canada') selected @endif value="canada">{{__('Canadá')}}</option>
                        <option @if($pais == 'catari') selected @endif value="catari">{{__('Catar')}}</option>
                        <option @if($pais == 'chade') selected @endif value="chade">{{__('Chade')}}</option>
                        <option @if($pais == 'chile') selected @endif value="chile">{{__('Chile')}}</option>
                        <option @if($pais == 'china') selected @endif value="china">{{__('China')}}</option>
                        <option @if($pais == 'chipre') selected @endif value="chipre">{{__('Chipre')}}</option>
                        <option @if($pais == 'colombia') selected @endif value="colombia">{{__('Colômbia')}}</option>
                        <option @if($pais == 'comores') selected @endif value="comores">{{__('Comores')}}</option>
                        <option @if($pais == 'congo') selected @endif value="congo">{{__('Congo')}}</option>
                        <option @if($pais == 'coreia_do_norte') selected @endif value="coreia_do_norte">{{__('Coreia do Norte')}}</option>
                        <option @if($pais == 'coreia_do_sul') selected @endif value="coreia_do_sul">{{__('Coreia do Sul')}}</option>
                        <option @if($pais == 'croacia') selected @endif value="croacia">{{__('Croácia')}}</option>
                        <option @if($pais == 'cuba') selected @endif value="cuba">{{__('Cuba')}}</option>
                        <option @if($pais == 'dinamarca') selected @endif value="dinamarca">{{__('Dinamarca')}}</option>
                        <option @if($pais == 'dominica') selected @endif value="dominica">{{__('Dominica')}}</option>
                        <option @if($pais == 'egito') selected @endif value="egito">{{__('Egito')}}</option>
                        <option @if($pais == 'el_salvador') selected @endif value="el_salvador">{{__('El Salvador')}}</option>
                        <option @if($pais == 'embudos') selected @endif value="embudos">{{__('Embudos')}}</option>
                        <option @if($pais == 'emirados_arabes_unidos') selected @endif value="emirados_arabes_unidos">{{__('Emirados Árabes Unidos')}}</option>
                        <option @if($pais == 'equador') selected @endif value="equador">{{__('Equador')}}</option>
                        <option @if($pais == 'eritrea') selected @endif value="eritrea">{{__('Eritreia')}}</option>
                        <option @if($pais == 'eslovaquia') selected @endif value="eslovaquia">{{__('Eslováquia')}}</option>
                        <option @if($pais == 'eslovenia') selected @endif value="eslovenia">{{__('Eslovênia')}}</option>
                        <option @if($pais == 'espanha') selected @endif value="espanha">{{__('Espanha')}}</option>
                        <option @if($pais == 'estados_unidos') selected @endif value="estados_unidos">{{__('Estados Unidos da América')}}</option>
                        <option @if($pais == 'estonia') selected @endif value="estonia">{{__('Estônia')}}</option>
                        <option @if($pais == 'etiópia') selected @endif value="etopia">{{__('Etiópia')}}</option>
                        <option @if($pais == 'fiji') selected @endif value="fiji">{{__('Fiji')}}</option>
                        <option @if($pais == 'filipinas') selected @endif value="filipinas">{{__('Filipinas')}}</option>
                        <option @if($pais == 'finlandia') selected @endif value="finlandia">{{__('Finlândia')}}</option>
                        <option @if($pais == 'franca') selected @endif value="franca">{{__('França')}}</option>
                        <option @if($pais == 'gabon') selected @endif value="gabon">{{__('Gabão')}}</option>
                        <option @if($pais == 'gambia') selected @endif value="gambia">{{__('Gâmbia')}}</option>
                        <option @if($pais == 'georgia') selected @endif value="georgia">{{__('Geórgia')}}</option>
                        <option @if($pais == 'gibraltar') selected @endif value="gibraltar">{{__('Gibraltar')}}</option>
                        <option @if($pais == 'granada') selected @endif value="granada">{{__('Granada')}}</option>
                        <option @if($pais == 'greece') selected @endif value="greece">{{__('Grécia')}}</option>
                        <option @if($pais == 'guatemala') selected @endif value="guatemala">{{__('Guatemala')}}</option>
                        <option @if($pais == 'guinea') selected @endif value="guinea">{{__('Guiné')}}</option>
                        <option @if($pais == 'guine_bissau') selected @endif value="guine_bissau">{{__('Guiné-Bissau')}}</option>
                        <option @if($pais == 'guyana') selected @endif value="guyana">{{__('Guiana')}}</option>
                        <option @if($pais == 'haiti') selected @endif value="haiti">{{__('Haiti')}}</option>
                        <option @if($pais == 'honduras') selected @endif value="honduras">{{__('Honduras')}}</option>
                        <option @if($pais == 'hong_kong') selected @endif value="hong_kong">{{__('Hong Kong')}}</option>
                        <option @if($pais == 'hungria') selected @endif value="hungria">{{__('Hungria')}}</option>
                        <option @if($pais == 'iacos') selected @endif value="iacos">{{__('Iacós')}}</option>
                        <option @if($pais == 'islândia') selected @endif value="islandia">{{__('Islândia')}}</option>
                        <option @if($pais == 'india') selected @endif value="india">{{__('Índia')}}</option>
                        <option @if($pais == 'indonesia') selected @endif value="indonesia">{{__('Indonésia')}}</option>
                        <option @if($pais == 'irlanda') selected @endif value="irlanda">{{__('Irlanda')}}</option>
                        <option @if($pais == 'irã') selected @endif value="ira">{{__('Irã')}}</option>
                        <option @if($pais == 'iraque') selected @endif value="iraque">{{__('Iraque')}}</option>
                        <option @if($pais == 'israel') selected @endif value="israel">{{__('Israel')}}</option>
                        <option @if($pais == 'italia') selected @endif value="italia">{{__('Itália')}}</option>
                        <option @if($pais == 'jamaica') selected @endif value="jamaica">{{__('Jamaica')}}</option>
                        <option @if($pais == 'japão') selected @endif value="japao">{{__('Japão')}}</option>
                        <option @if($pais == 'jordania') selected @endif value="jordania">{{__('Jordânia')}}</option>
                        <option @if($pais == 'juliano') selected @endif value="juliano">{{__('Juliano')}}</option>
                        <option @if($pais == 'laos') selected @endif value="laos">{{__('Laos')}}</option>
                        <option @if($pais == 'lesoto') selected @endif value="lesoto">{{__('Lesoto')}}</option>
                        <option @if($pais == 'letonia') selected @endif value="letonia">{{__('Letônia')}}</option>
                        <option @if($pais == 'libano') selected @endif value="libano">{{__('Líbano')}}</option>
                        <option @if($pais == 'liberia') selected @endif value="liberia">{{__('Libéria')}}</option>
                        <option @if($pais == 'liechtenstein') selected @endif value="liechtenstein">{{__('Liechtenstein')}}</option>
                        <option @if($pais == 'lituania') selected @endif value="lituania">{{__('Lituânia')}}</option>
                        <option @if($pais == 'luxemburgo') selected @endif value="luxemburgo">{{__('Luxemburgo')}}</option>
                        <option @if($pais == 'macedonia') selected @endif value="macedonia">{{__('Macedônia')}}</option>
                        <option @if($pais == 'madagascar') selected @endif value="madagascar">{{__('Madagáscar')}}</option>
                        <option @if($pais == 'malasia') selected @endif value="malasia">{{__('Malásia')}}</option>
                        <option @if($pais == 'malaui') selected @endif value="malaui">{{__('Malawi')}}</option>
                        <option @if($pais == 'maldivas') selected @endif value="maldivas">{{__('Maldivas')}}</option>
                        <option @if($pais == 'mali') selected @endif value="mali">{{__('Mali')}}</option>
                        <option @if($pais == 'malta') selected @endif value="malta">{{__('Malta')}}</option>
                        <option @if($pais == 'marianas') selected @endif value="marianas">{{__('Marianas')}}</option>
                        <option @if($pais == 'marrocos') selected @endif value="marrocos">{{__('Marrocos')}}</option>
                        <option @if($pais == 'martinica') selected @endif value="martinica">{{__('Martinica')}}</option>
                        <option @if($pais == 'mauricio') selected @endif value="mauricio">{{__('Maurício')}}</option>
                        <option @if($pais == 'mauritania') selected @endif value="mauritania">{{__('Mauritânia')}}</option>
                        <option @if($pais == 'mexico') selected @endif value="mexico">{{__('México')}}</option>
                        <option @if($pais == 'micronesia') selected @endif value="micronesia">{{__('Micronésia')}}</option>
                        <option @if($pais == 'moçambique') selected @endif value="mocambique">{{__('Moçambique')}}</option>
                        <option @if($pais == 'moldavia') selected @endif value="moldavia">{{__('Moldávia')}}</option>
                        <option @if($pais == 'monaco') selected @endif value="monaco">{{__('Mônaco')}}</option>
                        <option @if($pais == 'mongolia') selected @endif value="mongolia">{{__('Mongólia')}}</option>
                        <option @if($pais == 'montenegro') selected @endif value="montenegro">{{__('Montenegro')}}</option>
                        <option @if($pais == 'namibia') selected @endif value="namibia">{{__('Namíbia')}}</option>
                        <option @if($pais == 'nauru') selected @endif value="nauru">{{__('Nauru')}}</option>
                        <option @if($pais == 'nepal') selected @endif value="nepal">{{__('Nepal')}}</option>
                        <option @if($pais == 'nicaragua') selected @endif value="nicaragua">{{__('Nicarágua')}}</option>
                        <option @if($pais == 'niger') selected @endif value="niger">{{__('Níger')}}</option>
                        <option @if($pais == 'nigeria') selected @endif value="nigeria">{{__('Nigéria')}}</option>
                        <option @if($pais == 'niue') selected @endif value="niue">{{__('Niue')}}</option>
                        <option @if($pais == 'nova_zelandia') selected @endif value="nova_zelandia">{{__('Nova Zelândia')}}</option>
                        <option @if($pais == 'nicaragua') selected @endif value="nicaragua">{{__('Nicarágua')}}</option>
                        <option @if($pais == 'noruega') selected @endif value="noruega">{{__('Noruega')}}</option>
                        <option @if($pais == 'nova_zelandia') selected @endif value="nova_zelandia">{{__('Nova Zelândia')}}</option>
                        <option @if($pais == 'panama') selected @endif value="panama">{{__('Panamá')}}</option>
                        <option @if($pais == 'papua_nova_guinea') selected @endif value="papua_nova_guinea">{{__('Papua Nova Guiné')}}</option>
                        <option @if($pais == 'paquistao') selected @endif value="paquistao">{{__('Paquistão')}}</option>
                        <option @if($pais == 'paraguai') selected @endif value="paraguai">{{__('Paraguai')}}</option>
                        <option @if($pais == 'peru') selected @endif value="peru">{{__('Peru')}}</option>
                        <option @if($pais == 'polonia') selected @endif value="polonia">{{__('Polônia')}}</option>
                        <option @if($pais == 'portugal') selected @endif value="portugal">{{__('Portugal')}}</option>
                        <option @if($pais == 'quenia') selected @endif value="kenia">{{__('Quênia')}}</option>
                        <option @if($pais == 'quiribati') selected @endif value="quiribati">{{__('Quiribati')}}</option>
                        <option @if($pais == 'reino_unido') selected @endif value="reino_unido">{{__('Reino Unido')}}</option>
                        <option @if($pais == 'república_dominicana') selected @endif value="republica_dominicana">{{__('República Dominicana')}}</option>
                        <option @if($pais == 'república_checa') selected @endif value="republica_checa">{{__('República Checa')}}</option>
                        <option @if($pais == 'ruanda') selected @endif value="ruanda">{{__('Ruanda')}}</option>
                        <option @if($pais == 'romênia') selected @endif value="romenia">{{__('Romênia')}}</option>
                        <option @if($pais == 'rússia') selected @endif value="russia">{{__('Rússia')}}</option>
                        <option @if($pais == 'saint_kitts_nevis') selected @endif value="saint_kitts_nevis">{{__('Saint Kitts e Nevis')}}</option>
                        <option @if($pais == 'saint_lucia') selected @endif value="saint_lucia">{{__('Saint Lucia')}}</option>
                        <option @if($pais == 'samoa') selected @endif value="samoa">{{__('Samoa')}}</option>
                        <option @if($pais == 'san_marino') selected @endif value="san_marino">{{__('San Marino')}}</option>
                        <option @if($pais == 'santa_lucia') selected @endif value="santa_lucia">{{__('Santa Lúcia')}}</option>
                        <option @if($pais == 'senegal') selected @endif value="senegal">{{__('Senegal')}}</option>
                        <option @if($pais == 'serra_leoa') selected @endif value="serra_leoa">{{__('Serra Leoa')}}</option>
                        <option @if($pais == 'seychelles') selected @endif value="seychelles">{{__('Seicheles')}}</option>
                        <option @if($pais == 'singapura') selected @endif value="singapura">{{__('Singapura')}}</option>
                        <option @if($pais == 'siria') selected @endif value="siria">{{__('Síria')}}</option>
                        <option @if($pais == 'somalia') selected @endif value="somalia">{{__('Somália')}}</option>
                        <option @if($pais == 'sri_lanka') selected @endif value="sri_lanka">{{__('Sri Lanka')}}</option>
                        <option @if($pais == 'sudan') selected @endif value="sudan">{{__('Sudão')}}</option>
                        <option @if($pais == 'suriname') selected @endif value="suriname">{{__('Suriname')}}</option>
                        <option @if($pais == 'suécia') selected @endif value="suecia">{{__('Suécia')}}</option>
                        <option @if($pais == 'suíça') selected @endif value="suica">{{__('Suíça')}}</option>
                        <option @if($pais == 'sri_lanka') selected @endif value="sri_lanka">{{__('Sri Lanka')}}</option>
                        <option @if($pais == 'tailandia') selected @endif value="tailandia">{{__('Tailândia')}}</option>
                        <option @if($pais == 'taishe') selected @endif value="taishe">{{__('Taishe')}}</option>
                        <option @if($pais == 'taiwan') selected @endif value="taiwan">{{__('Taiwan')}}</option>
                        <option @if($pais == 'tanzânia') selected @endif value="tanzania">{{__('Tanzânia')}}</option>
                        <option @if($pais == 'timor_leste') selected @endif value="timor_leste">{{__('Timor Leste')}}</option>
                        <option @if($pais == 'togo') selected @endif value="togo">{{__('Togo')}}</option>
                        <option @if($pais == 'tonga') selected @endif value="tonga">{{__('Tonga')}}</option>
                        <option @if($pais == 'trinidad_tobago') selected @endif value="trinidad_tobago">{{__('Trinidad e Tobago')}}</option>
                        <option @if($pais == 'tunisia') selected @endif value="tunisia">{{__('Tunísia')}}</option>
                        <option @if($pais == 'turcomenistão') selected @endif value="turcomenistao">{{__('Turcomenistão')}}</option>
                        <option @if($pais == 'turquia') selected @endif value="turquia">{{__('Turquia')}}</option>
                        <option @if($pais == 'tuvalu') selected @endif value="tuvalu">{{__('Tuvalu')}}</option>
                        <option @if($pais == 'uganda') selected @endif value="uganda">{{__('Uganda')}}</option>
                        <option @if($pais == 'uruguai') selected @endif value="uruguai">{{__('Uruguai')}}</option>
                        <option @if($pais == 'uzbequistão') selected @endif value="uzbequistao">{{__('Uzbequistão')}}</option>
                        <option @if($pais == 'vanuatu') selected @endif value="vanuatu">{{__('Vanuatu')}}</option>
                        <option @if($pais == 'vaticano') selected @endif value="vaticano">{{__('Vaticano')}}</option>
                        <option @if($pais == 'venezuela') selected @endif value="venezuela">{{__('Venezuela')}}</option>
                        <option @if($pais == 'vietnam') selected @endif value="vietname">{{__('Vietnã')}}</option>
                        <option @if($pais == 'wallis_futuna') selected @endif value="wallis_futuna">{{__('Wallis e Futuna')}}</option>
                        <option @if($pais == 'zambia') selected @endif value="zambia">{{__('Zâmbia')}}</option>
                        <option @if($pais == 'zimbabue') selected @endif value="zimbabue">{{__('Zimbábue')}}</option>
                    </select>
                    <input type="hidden" name="pais" value="{{$pais}}">

                    @error('pais')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="etapas" style="font-weight: 500;">
                <div class="etapa ativa">
                    <p>1. {{ __('Validação de cadastro') }}</p>
                </div>
                <div class="etapa">
                    <p>2. {{__('Informações de cadastro')}}</p>
                </div>
            </div>

            {{-- Nome | CPF | E-mail --}}
            <div class="container card">
                <br>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="nome" class="col-form-label required-field">{{ __('Nome completo') }}</label>
                        <input id="nome" type="text" class="form-control apenasLetras @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}"  autocomplete="nome" autofocus required>

                        @error('nome')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-3">
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
                            <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" value="{{ old('cpf') }}" 
                                autocomplete="cpf" placeholder="CPF" minlength="14" maxlength="14" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" title="O CPF deve conter 11 caracteres" autofocus>

                            @error('cpf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div id="fieldCNPJ" @error('passaporte') style="display: block" @enderror style="display: none" >
                            <input id="cnpj" type="text" class="form-control @error('cnpj') is-invalid @enderror" name="cnpj" placeholder="{{__('CNPJ')}}" 
                                value="{{ old('cnpj') }}"  autocomplete="cnpj" minlength="18" maxlength="18" pattern="\d{2}\.\d{3}\.\d{3}/\d{4}-\d{2}" title="O CNPJ deve conter 14 caracteres" autofocus>

                            @error('cnpj')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div id="fieldPassaporte" @error('passaporte') style="display: block" @enderror style="display: none" >
                            <input id="passaporte" type="text" class="form-control @error('passaporte') is-invalid @enderror" name="passaporte" minlength="6" maxlength="9" pattern="[A-Za-z0-9]{6,9}"
                                title="O passaporte deve conter apenas letras e números, com 6 a 9 caracteres." placeholder="{{__('Passaporte')}}" value="{{ old('passaporte') }}"  autocomplete="passaporte" autofocus>

                            @error('passaporte')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="col-form-label required-field">{{ __('E-mail') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" required>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row form-group mb-3">
                    <div class="col-md-10"></div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100" style="background-color: #034652; color: white; border-color: #034652;">
                            {{ __('Continuar') }}
                        </button>
                    </div>
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
            $("#fieldPassaporte").hide().find('input').val('');;
            $("#fieldCNPJ").hide().find('input').val('');;
            $("#fieldCPF").show();
        });

        $("#customRadioInline2").click(function(){
            $("#fieldPassaporte").hide().find('input').val('');;
            $("#fieldCNPJ").show();
            $("#fieldCPF").hide().find('input').val('');;
        });

        $("#customRadioInline3").click(function(){
            $("#fieldPassaporte").show();
            $("#fieldCNPJ").hide().find('input').val('');;
            $("#fieldCPF").hide().find('input').val('');;
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

  </script>

    @section('javascript')
        <script>
            $(document).ready(function () {
                $('#pais').select2();
            });
        </script>
    @endsection
@endsection