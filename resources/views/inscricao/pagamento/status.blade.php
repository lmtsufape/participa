@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div id="statusScreenBrick_container"></div>

                @if(isset($pagamento))
                    @php
                        $statusPermitemRetry = ['rejected', 'cancelled', 'expired', 'refunded', 'charged_back'];
                    @endphp

                    @if(in_array($pagamento->status, $statusPermitemRetry))
                        <div class="card mt-4">
                            <div class="card-body text-center">
                                <h5 class="card-title text-danger">Pagamento não aprovado</h5>
                                <p class="card-text">
                                    Seu pagamento não foi aprovado. Você pode tentar novamente com outro método de pagamento.
                                </p>
                                <form action="{{ route('checkout.novaTentativa', $pagamento->inscricao->evento) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <img src="{{ asset('img/icons/reload.svg') }}" alt="" style="width:16px; height:16px; margin-right:6px;"> Tentar Novamente
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    @parent
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const key = @json($key);
        const mp = new MercadoPago(key, {
            locale: 'pt-BR'
        });
        const bricksBuilder = mp.bricks();
        const pagamento = @json($pagamento);
        const renderStatusScreenBrick = async (bricksBuilder) => {
            const settings = {
                initialization: {
                    paymentId: pagamento.codigo, // Payment identifier, from which the status will be checked
                },
                customization: {
                    visual: {
                        style: {
                            customVariables: {
                                "baseColor": "#114048",
                            },
                            theme: "bootstrap",
                        }
                    },
                    backUrls: {
                    }
                },
                callbacks: {
                    onReady: () => {
                        // Callback called when Brick is ready
                    },
                    onError: (error) => {
                        // Callback called for all Brick error cases
                    },
                },
            };
            window.statusScreenBrickController = await bricksBuilder.create('statusScreen',
                'statusScreenBrick_container', settings);
        };
        renderStatusScreenBrick(bricksBuilder);
    </script>
@endsection
