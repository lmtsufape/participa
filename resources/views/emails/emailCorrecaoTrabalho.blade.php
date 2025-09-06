@component('mail::message')
# Correção de Trabalho Enviada

Olá **{{ $revisor->user->name }}**,

O(A) autor(a) do trabalho **"{{ $trabalho->titulo }}"** enviou uma correção do texto para o evento **{{ $evento->nome }}**.

A correção está disponível para análise no sistema.

Atenciosamente,
Comissão Científica do {{$trabalho->evento->nome}}

@component('mail::button', ['url' => route('revisor.index')])
Acessar Sistema
@endcomponent

@endcomponent
