<x-mail::message>

Caro(a) {{ $user->name }} e coautor/a(es/as) do trabalho {{$trabalho->titulo}} submetido(s) ao {{$trabalho->evento->nome}},

Lembramos a todos(as) que o prazo de envio de versões corrigidas dos textos, com ou sem identificação dos autores (ver regras do evento) se encerra {{$fimCorrecao}}.

Pedimos que enviem nos prazos requeridos para evitar atrasos na elaboração da publicação dos trabalhos.

Quaisquer dúvidas, entrar em contato com o e-mail do evento: {{$trabalho->evento->email}}.

Atenciosamente,
Comissão Científica do {{$trabalho->evento->nome}}
</x-mail::message>
