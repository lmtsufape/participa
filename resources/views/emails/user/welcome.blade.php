@component('mail::message')
# Seja Bem-Vindo

The body of your message.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

@include('emails.footer')
@endcomponent
