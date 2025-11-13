/**
 * Validação de CPF
 */
function validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g, '');

    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
        return false;
    }

    let soma = 0;
    let resto;

    for (let i = 1; i <= 9; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
    }

    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) return false;

    soma = 0;
    for (let i = 1; i <= 10; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
    }

    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(10, 11))) return false;

    return true;
}

/**
 * Validação de CNPJ
 */
function validarCNPJ(cnpj) {
    cnpj = cnpj.replace(/[^\d]+/g, '');

    if (cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) {
        return false;
    }

    let tamanho = cnpj.length - 2;
    let numeros = cnpj.substring(0, tamanho);
    let digitos = cnpj.substring(tamanho);
    let soma = 0;
    let pos = tamanho - 7;

    for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
    }

    let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0)) return false;

    tamanho = tamanho + 1;
    numeros = cnpj.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;

    for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
    }

    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1)) return false;

    return true;
}

/**
 * Validação de Passaporte
 */
function validarPassaporte(passaporte) {
    // Remove espaços e converte para maiúsculas
    passaporte = passaporte.trim().toUpperCase();

    // Verifica se tem entre 6 e 9 caracteres alfanuméricos
    const regex = /^[A-Z0-9]{6,9}$/;
    return regex.test(passaporte);
}

/**
 * Mostrar mensagem de erro
 */
function mostrarErroDocumento(campo, mensagem) {
    const $campo = $(campo);
    $campo.addClass('is-invalid');

    // Remove erro anterior se existir
    $campo.siblings('.invalid-feedback').remove();

    // Adiciona nova mensagem de erro
    $campo.after(`<span class="invalid-feedback" role="alert" style="display: block;"><strong>${mensagem}</strong></span>`);
}

/**
 * Limpar mensagem de erro
 */
function limparErroDocumento(campo) {
    const $campo = $(campo);
    $campo.removeClass('is-invalid');
    $campo.siblings('.invalid-feedback').remove();
}
