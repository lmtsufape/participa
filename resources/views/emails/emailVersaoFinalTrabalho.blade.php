@component('mail::message')
# Versão final de trabalho enviada

Olá **{{ $nomeDestinatario }}**,

A versão final do trabalho **"{{ $trabalho->titulo }}"** foi recebida com sucesso para o evento **{{ $trabalho->evento->nome }}**.

O arquivo está disponível para consulta no sistema. Os arquivos anteriores foram preservados.

@component('mail::button', ['url' => route('trabalho.versao-final.index', $trabalho)])
Consultar versão final
@endcomponent

Atenciosamente,

Comissão Científica do {{ $trabalho->evento->nome }}
@endcomponent
