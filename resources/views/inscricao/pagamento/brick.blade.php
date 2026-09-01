@extends('layouts.app')

@section('content')
    <div class="alert alert-success text-center">
        <span class="fw-bold text-center" style="font-size: 20px;">
            Conclua o pagamento para garantir a sua inscrição!
        </span>

    </div>

<div id="paymentBrick_container">
</div>
<div id="payment-error" class="alert alert-danger d-none mt-3" role="alert"></div>
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
    const categoria = @json($categoria);
    const user = @json($user);
    const endereco = user.endereco || {};
    const inscricao = @json($inscricao);
    const evento = @json($evento);
    const renderPaymentBrick = async (bricksBuilder) => {
        const settings = {
            initialization: {
                /*
                    "amount" é a quantia total a pagar por todos os meios de pagamento com exceção da Conta Mercado Pago e Parcelas sem cartão de crédito, que têm seus valores de processamento determinados no backend através do "preferenceId"
                */
                amount: categoria.valor_total,
                payer: {
                    firstName: user.name.split(' ').slice(0, -1).join(" "),
                    lastName: user.name.split(' ').pop(),
                    identification: {
                        "type": "CPF",
                        "number": user.cpf,
                    },
                    email: user.email,
                    address: {
                        zipCode: endereco.cep || '',
                        federalUnit: endereco.uf || '',
                        city: endereco.cidade || '',
                        neighborhood: endereco.bairro || '',
                        streetName: endereco.rua || '',
                        streetNumber: endereco.numero || '',
                        complement: endereco.complemento || '',
                    },
                },
            },
            customization: {
                visual: {
                    style: {
                        customVariables: {
                            "baseColor": "#114048",
                        },
                        theme: "bootstrap",
                    },
                },
                paymentMethods: {
                    atm: "all",
                    creditCard: "all",
                    bankTransfer: "all"
                },
                installments: 1,
            },
            callbacks: {
                onReady: () => {
                    /*
                    Callback chamado quando o Brick está pronto.
                    Aqui, você pode ocultar seu site, por exemplo.
                    */
                },
                onSubmit: ({
                    selectedPaymentMethod,
                    formData
                }) => {
                    formData.evento = evento.id;
                    // callback chamado quando há click no botão de envio de dados
                    return new Promise((resolve, reject) => {
                        fetch("/checkout/process_payment", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "Accept": "application/json",
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                },
                                body: JSON.stringify(formData),
                            })
                            .then(async (response) => {
                                if (!response.ok) {
                                    const data = await response.json().catch(() => ({}));
                                    throw new Error(data.message || 'Não foi possível processar o pagamento. Tente novamente.');
                                }
                                window.location.href = '/checkout/status-pagamento/' + evento.id;
                                resolve();
                            })
                            .catch((error) => {
                                const errorContainer = document.getElementById('payment-error');
                                errorContainer.textContent = error.message;
                                errorContainer.classList.remove('d-none');
                                reject();
                            });
                    });
                },
                onError: (error) => {
                    console.error(error);
                    const errorContainer = document.getElementById('payment-error');
                    errorContainer.textContent = 'Não foi possível carregar o formulário de pagamento. Atualize a página e tente novamente.';
                    errorContainer.classList.remove('d-none');
                },
            },
        };
        window.paymentBrickController = await bricksBuilder.create(
            "payment",
            "paymentBrick_container",
            settings
        );
    };
    renderPaymentBrick(bricksBuilder);
</script>
@endsection
