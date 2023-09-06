@extends('layouts.app')

@section('content')
    <div class="container w-50"><div id="statusScreenBrick_container"></div></div>
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
