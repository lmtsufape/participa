@component('mail::message')
# Olá {{ $user->name }}!

Este e-mail é um lembrete automático sobre trabalhos/atividades que você tem para avaliar no evento **"{{ $evento->nome }}"**.

## Trabalhos pendentes de avaliação:
**{{ $trabalhos }}**

## ⚠️ Prazo de avaliação:
**{{ date('d/m/Y H:i:s', strtotime($dataLimite)) }}**

@if($diasRestantes == 1)
**🚨 ATENÇÃO: Resta apenas 1 dia para concluir a avaliação!**
@else
**⏰ Restam {{ $diasRestantes }} dias para concluir a avaliação.**
@endif

Agradecemos de antemão pela sua disponibilidade para colaborar com a realização deste evento.

@component('mail::button', ['url' => route('login')])
Acessar sistema
@endcomponent

Em caso de dúvidas, entre em contato com a coordenação: {{ $coord->email }}

@include('emails.footer')
@endcomponent
