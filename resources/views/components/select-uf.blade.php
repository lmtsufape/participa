@props([
    'uf',
    'name' => 'uf'
])

<div class="col-sm-4" id="groupformuf">
    <label for="uf" class="col-form-label">{{ __('UF') }}*</label>
    <select class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf">
        <option value="" disabled selected hidden>-- {{__('UF')}} --</option>
        <option @if($uf == 'AC') selected @endif value="AC">Acre</option>
        <option @if($uf == 'AL') selected @endif value="AL">Alagoas</option>
        <option @if($uf == 'AP') selected @endif value="AP">Amapá</option>
        <option @if($uf == 'AM') selected @endif value="AM">Amazonas</option>
        <option @if($uf == 'BA') selected @endif value="BA">Bahia</option>
        <option @if($uf == 'CE') selected @endif value="CE">Ceará</option>
        <option @if($uf == 'DF') selected @endif value="DF">Distrito Federal</option>
        <option @if($uf == 'ES') selected @endif value="ES">Espírito Santo</option>
        <option @if($uf == 'GO') selected @endif value="GO">Goiás</option>
        <option @if($uf == 'MA') selected @endif value="MA">Maranhão</option>
        <option @if($uf == 'MT') selected @endif value="MT">Mato Grosso</option>
        <option @if($uf == 'MS') selected @endif value="MS">Mato Grosso do Sul</option>
        <option @if($uf == 'MG') selected @endif value="MG">Minas Gerais</option>
        <option @if($uf == 'PA') selected @endif value="PA">Pará</option>
        <option @if($uf == 'PB') selected @endif value="PB">Paraíba</option>
        <option @if($uf == 'PR') selected @endif value="PR">Paraná</option>
        <option @if($uf == 'PE') selected @endif value="PE">Pernambuco</option>
        <option @if($uf == 'PI') selected @endif value="PI">Piauí</option>
        <option @if($uf == 'RJ') selected @endif value="RJ">Rio de Janeiro</option>
        <option @if($uf == 'RN') selected @endif value="RN">Rio Grande do Norte</option>
        <option @if($uf == 'RS') selected @endif value="RS">Rio Grande do Sul</option>
        <option @if($uf == 'RO') selected @endif value="RO">Rondônia</option>
        <option @if($uf == 'RR') selected @endif value="RR">Roraima</option>
        <option @if($uf == 'SC') selected @endif value="SC">Santa Catarina</option>
        <option @if($uf == 'SP') selected @endif value="SP">São Paulo</option>
        <option @if($uf == 'SE') selected @endif value="SE">Sergipe</option>
        <option @if($uf == 'TO') selected @endif value="TO">Tocantins</option>
    </select>

    @error('uf')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
