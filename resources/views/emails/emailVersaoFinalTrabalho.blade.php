@component('mail::message')
# Trabalho completo enviado

Olá **{{ $nomeDestinatario }}**,

O trabalho completo **"{{ $trabalho->titulo }}"** foi recebido com sucesso para o evento **{{ $trabalho->evento->nome }}**.

O arquivo está disponível para consulta no sistema. Os arquivos anteriores foram preservados.

@component('mail::button', ['url' => route('trabalho.versao-final.index', $trabalho)])
Consultar trabalho completo
@endcomponent

Atenciosamente,

Comissão Científica do {{ $trabalho->evento->nome }}
@endcomponent
