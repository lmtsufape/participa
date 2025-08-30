<x-mail::message>

Caro(a) {{ $user->name }} e coautor/a(es/as) do trabalho {{$trabalho->titulo}} submetido(s) ao {{$trabalho->evento->nome}},

Lembramos que você tem 05 dias para revisar e enviar corrigido pelo sistema. Não se esqueça de adicionar nomes, vínculos e e-mails nessa segunda submissão, conforme Edital.

Pedimos que enviem nos prazos requeridos para evitar atrasos na elaboração da publicação dos trabalhos.

Atenciosamente,
Comissão Científica do {{$trabalho->evento->nome}}
</x-mail::message>
