@extends('layouts.app')

@section('content')
    <div class="alert alert-success text-center">
        <span class="fw-bold text-center" style="font-size: 20px;">
            Conclua o pagamento para garantir a sua Associação!
        </span>

    </div>

<div id="paymentBrick_container">
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
    
    // Variáveis simplificadas para Associação
    const tipo = @json($tipo);
    const user = @json($user);
    const valorAnuidade = @json($valor); // Pegando o valor que vem do controller

    const renderPaymentBrick = async (bricksBuilder) => {
        const settings = {
            initialization: {
                // Aqui usamos o valor direto da anuidade
                amount: valorAnuidade, 
                payer: {
                    firstName: user.name.split(' ').slice(0, -1).join(" "),
                    lastName: user.name.split(' ').pop(),
                    identification: {
                        "type": "CPF",
                        "number": user.cpf,
                    },
                    email: user.email,
                    address: {
                        zipCode: user.endereco ? user.endereco.cep : '',
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
                            "baseColor": "#114048",
                        },
                        theme: "bootstrap",
                    },
                },
                paymentMethods: {
                    atm: "all",
                    creditCard: "all",
                    bankTransfer: "all",
                    ticket: "all"
                },
                installments: 1,
            },
            callbacks: {
                onReady: () => {},
                onSubmit: ({ selectedPaymentMethod, formData }) => {
                    // Importante: Passar o tipo para o backend processar
                    formData.tipo_associado = tipo; 
                    
                    return new Promise((resolve, reject) => {
                        fetch("{{ route('associar.processar') }}", { // Usando o name da rota
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            body: JSON.stringify(formData),
                        })
                        .then((response) => response.json())
                        .then((result) => {
                            if(result.status === 'success') {
                                window.location.href = '/perfil?sucesso=associado';
                            } else {
                                alert("Erro ao processar: " + result.message);
                            }
                            resolve();
                        })
                        .catch(() => reject());
                    });
                },
                onError: (error) => {
                    console.error(error);
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