@props(['evento'])

<style>
    .evento-card {
        height: 100%;
        overflow: hidden;

        background: #fff;

        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;

        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .evento-card:hover {
        transform: translateY(-2px);

        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
    }

    .evento-card__image-container {
        position: relative;

        height: 150px;
        overflow: hidden;

        background: #f1f5f9;
    }

    .evento-card__image {
        width: 100%;
        height: 100%;

        object-fit: cover;

        transition: transform 0.3s ease;
    }

    .evento-card:hover .evento-card__image {
        transform: scale(1.02);
    }

    .evento-card__image-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 100%;
        height: 100%;

        color: #94a3b8;

        font-size: 2rem;

        background: #f1f5f9;
    }


    .evento-card__status {
        position: absolute;

        top: 0.75rem;
        left: 0.75rem;

        display: inline-flex;
        align-items: center;

        gap: 0.4rem;

        padding: 0.35rem 0.65rem;

        border-radius: 999px;

        font-size: 0.75rem;
        font-weight: 600;

        backdrop-filter: blur(6px);
    }

    .evento-card__status-dot {
        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: currentColor;
    }

    .evento-card__status--published {
        color: #08783e;

        background: rgba(220, 252, 231, 0.95);
    }

    .evento-card__status--progress {
        color: #166534;

        background: rgba(220, 252, 231, 0.95);
    }

    .evento-card__status--draft {
        color: #b45309;

        background: rgba(254, 243, 199, 0.95);
    }

    .evento-card__status--closed {
        color: #475569;

        background: rgba(241, 245, 249, 0.95);
    }


    .evento-card__body {
        display: flex;
        flex-direction: column;

        height: calc(100% - 150px);

        padding: 1rem;
    }

    .evento-card__meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        gap: 0.6rem 1rem;

        margin-bottom: 0.7rem;

        color: #64748b;

        font-size: 0.78rem;
    }

    .evento-card__meta-item {
        display: flex;
        align-items: center;

        gap: 0.4rem;
    }

    .evento-card__meta-item i {
        color: #475569;
    }


    .evento-card__title {
        margin: 0 0 0.45rem;

        color: #0f172a;

        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.3;
    }

    .evento-card__description {
        display: -webkit-box;
        overflow: hidden;

        margin: 0 0 1rem;

        color: #64748b;

        font-size: 0.875rem;
        line-height: 1.45;

        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }


    .evento-card__details {
        display: flex;

        gap: 1.5rem;

        margin-top: auto;
        margin-bottom: 1rem;
    }

    .evento-card__detail {
        display: flex;
        align-items: center;

        gap: 0.6rem;

        color: #475569;
    }

    .evento-card__detail>i {
        color: #475569;

        font-size: 1.1rem;
    }

    .evento-card__detail div {
        display: flex;
        flex-direction: column;

        line-height: 1.15;
    }

    .evento-card__detail strong {
        color: #0f172a;

        font-size: 0.9rem;
        font-weight: 700;
    }

    .evento-card__detail span {
        margin-top: 0.15rem;

        color: #64748b;

        font-size: 0.72rem;
    }


    .evento-card__delete-form {
        display: flex;

        margin: 0;
    }

    .evento-card__button {
        display: flex;
        align-items: center;
        justify-content: center;

        gap: 0.4rem;

        min-height: 38px;

        padding: 0.5rem 0.7rem;

        border: 1px solid transparent;
        border-radius: 0.5rem;

        font-size: 0.78rem;
        font-weight: 600;

        text-decoration: none;

        cursor: pointer;

        transition:
            background-color 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease;
    }


    @media (max-width: 575.98px) {

        .evento-card__details {
            gap: 1rem;
        }

        .evento-card__button--delete {
            width: 100%;
        }
    }
</style>
@php
    $status = match ($evento->publicado) {
        true => [
            'label' => 'Publicado',
            'class' => 'evento-card__status--published',
        ],

        default => [
            'label' => 'Rascunho',
            'class' => 'evento-card__status--draft',
        ],
    };
    use Illuminate\Support\Str;

@endphp

<article class="evento-card">

    <div class="evento-card__image-container">

        @if ($evento->is_multilingual && Session::get('idiomaAtual') === 'en' && !empty($evento->fotoEvento_en))
            <img src="{{ asset('storage/' . $evento->fotoEvento_en) }}" class="evento-card__image"
                alt="{{ $evento->nome_en }}">
        @elseif(!empty($evento->fotoEvento))
            <img src="{{ asset('storage/' . $evento->fotoEvento) }}" class="evento-card__image" alt="{{ $evento->nome }}">
        @else
            <div class="evento-card__image-placeholder">
                <i class="bi bi-calendar-event"></i>
            </div>
        @endif

        <span class="evento-card__status {{ $status['class'] }}">
            <span class="evento-card__status-dot"></span>

            {{ $status['label'] }}
        </span>

    </div>

    <div class="evento-card__body">

        <div class="evento-card__meta">

            <div class="evento-card__meta-item">
                <i class="bi bi-calendar3"></i>

                <span>
                    {{ $evento->dataInicio?->format('d/m/Y') }}

                    @if ($evento->dataFim)
                        a {{ $evento->dataFim->format('d/m/Y') }}
                    @endif
                </span>
            </div>

            @if ($evento->endereco)
                <div class="evento-card__meta-item">
                    <i class="bi bi-geo-alt-fill"></i>

                    <span>{{ $evento->endereco->getEnderecoFormatado() }}</span>
                </div>
            @endif

        </div>
        @php
            $titulo =
                $evento->is_multilingual && Session::get('idiomaAtual') === 'en' ? $evento->nome_en : $evento->nome;
            $descricao =
                $evento->is_multilingual && Session::get('idiomaAtual') === 'en'
                    ? $evento->descricao_en
                    : $evento->descricao;
        @endphp

        <h3 class="evento-card__title">
            {{ Str::limit($titulo, 30, '...') }}
        </h3>

        <p class="evento-card__description">
            {!! Str::limit(strip_tags($descricao), 100, '...') !!}
        </p>

        <div class="evento-card__details">

            <div class="evento-card__detail">
                <i class="bi bi-file-earmark-text"></i>

                <div>
                    <strong>
                        {{ $evento->modalidades_count ?? $evento->modalidades->count() }}
                    </strong>

                    <span>
                        {{ ($evento->modalidades_count ?? $evento->modalidades->count()) === 1 ? 'Modalidade' : 'Modalidades' }}
                    </span>
                </div>
            </div>

            <div class="evento-card__detail">
                <i class="bi bi-people-fill"></i>

                <div>
                    <strong>
                        {{ $evento->inscricoes_finalizadas_count ?? 0 }}
                    </strong>

                    <span>Inscrições</span>
                </div>
            </div>

        </div>

        <div class="d-flex gap-1">

            <a href="{{ route('evento.visualizar', ['id' => $evento->id]) }}"
                class="btn btn-sm btn-my-primary rounded-4 d-flex align-items-center gap-1">
                <i class="bi bi-eye-fill"></i>

                <span class="small">{{ __('Visualizar evento') }}</span>
            </a>

            <a href="{{ route('coord.detalhesEvento', ['eventoId' => $evento->id]) }}"
                class="btn btn-sm btn-secondary rounded-4 d-flex align-items-center gap-1">
                <i class="bi bi-gear-fill"></i>

                <span class="small">{{ __('Configurar evento') }}</span>
            </a>

            {{-- <form
                action="{{ route('evento.deletar', $evento->id) }}"
                method="POST"
                class="evento-card__delete-form"
                onsubmit="return confirm('Tem certeza que deseja apagar este evento?')"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-sm btn-danger rounded-4 d-flex align-items-center gap-1"
                >
                    <i class="bi bi-trash3"></i>

                    <span class="small">Apagar</span>
                </button>
            </form> --}}

        </div>

    </div>

</article>
