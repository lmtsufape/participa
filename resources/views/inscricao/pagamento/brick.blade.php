@extends('layouts.app')

@section('content')
    <div class="alert alert-success text-center">
        <span class="fw-bold text-center" style="font-size: 20px;">
            Conclua o pagamento para garantir a sua inscrição!
        </span>
    </div>

    <div class="container d-flex justify-content-center">
        <div class="col-md-8">
            <div id="paymentBrick_container"></div>
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
    const user = @json($user);
    const evento = @json($evento);
    
    const valorOriginal = @json($valorFinal);
    const valorFinalNum = parseFloat(String(valorOriginal).replace(',', '.'));

    const renderPaymentBrick = async (bricksBuilder) => {
        const settings = {
            initialization: {
                amount: valorFinalNum,
                payer: {
                    firstName: user.name ? user.name.split(' ')[0] : '',
                    lastName: user.name ? user.name.split(' ').slice(1).join(" ") : '',
                    identification: {
                        type: user.cnpj ? "CNPJ" : "CPF",
                        number: (user.cpf || user.cnpj || '').replace(/\D/g, ''),
                    },
                    email: user.email,
                    address: {
                        zipCode: user.endereco ? (user.endereco.cep || '').replace(/\D/g, '') : '',
                        federalUnit: user.endereco ? user.endereco.uf : '',
                        city: user.endereco ? user.endereco.cidade : '',
                        neighborhood: user.endereco ? user.endereco.bairro : '',
                        streetName: user.endereco ? user.endereco.rua : '',
                        streetNumber: user.endereco ? user.endereco.numero : '',
                        complement: user.endereco ? user.endereco.complemento : '',
                    },
                },
            },
            customization: {
                visual: {
                    style: {
                        customVariables: {
                            baseColor: "#114048",
                        },
                        theme: "bootstrap",
                    },
                },
                paymentMethods: {
                    creditCard: "all",
                    debitCard: "all",
                    bankTransfer: "all", // Pix
                    ticket: "all",       // Boleto
                },
                installments: 1,
            },
            callbacks: {
                onReady: () => {
                    // Brick carregado
                },
                onSubmit: ({ selectedPaymentMethod, formData }) => {
                    formData.evento = evento.id;
                    
                    return new Promise((resolve, reject) => {
                        fetch("{{ route('checkout.processPayment') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify(formData),
                        })
                        .then(async (response) => {
                            const resData = await response.json();
                            if (response.ok && resData.status === 'success') {
                                resolve();
                                window.location.href = resData.redirect_url;
                            } else {
                                alert(resData.message || 'Erro ao processar pagamento.');
                                reject();
                            }
                        })
                        .catch((error) => {
                            console.error('Erro na requisição:', error);
                            alert('Falha na comunicação com o servidor de pagamento.');
                            reject();
                        });
                    });
                },
                onError: (error) => {
                    console.error("Erro no Brick:", error);
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