@if ($atividade->vagas === null)
    <span class="badge bg-secondary">Vagas ilimitadas</span>
    <small class="d-block">Inscritos: {{ $atividade->users_count }}</small>
@else
    <strong class="d-block text-nowrap">Total: {{ (int) $atividade->vagas + $atividade->users_count }}</strong>
    <small class="d-block text-nowrap">Preenchidas: {{ $atividade->users_count }}</small>
    <small class="d-block text-nowrap">Disponíveis: {{ $atividade->vagas }}</small>
    @if ((int) $atividade->vagas <= 0)
        <span class="badge bg-danger">Vagas preenchidas</span>
    @endif
@endif
