@extends('layouts.app')


@section('content')

    <div class="container">
            <div class="row justify-content-center titulo">
                <div class="col-sm-12">
                    <h1>Dados para Boleto</h1>
                </div>
            </div>           
        <div class="col-md-6"> 
            
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Nome no Cartão</label>
                        <input type="text" class="form-control" name="card_name" value="GABRIEL ANTONIO">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Número do Cartão <span class="brand"></span></label>
                        <input type="text" class="form-control" name="card_number" value="4111111111111111">
                        <input type="hidden" name="card_brand">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Mês de Expiração</label>
                        <input type="text" class="form-control" name="card_month" value="12">
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Ano de Expiração</label>
                        <input type="text" class="form-control" name="card_year" value="2030">
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-5 form-group">
                        <label>Código de Segurança</label>
                        <input type="text" class="form-control" name="card_cvv" value="123">
                    </div>

                    <div class="col-md-12 installments form-group"></div>
                </div>

                <button class="btn btn-success btn-lg processCheckout">Efetuar Pagamento</button>
            </form>
        </div>
    </div>


@endsection

{{-- @section('javascript')
    <script src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>   
    <script src="{{ asset('assets/js/jquery.ajax.js') }}"></script>   
    
    <script type="text/javascript">
        window.addEventListener('load',function(){
            const sessionId = '{{session()->get('pagseguro_session_code')}}';
            

            PagSeguroDirectPayment.setSessionId(sessionId);
              
        });
    </script>

    <script type="text/javascript">
        window.addEventListener('load',function(){
            let amountTransaction = '{{ $total }}';
            console.log();
            let cardNumber = document.querySelector('input[name=card_number]');
            let spanBrand = document.querySelector('span.brand');
            
            cardNumber.addEventListener('keyup', function(){
                
                if(cardNumber.value.length >= 6){
                    PagSeguroDirectPayment.getBrand({
                        cardBin: cardNumber.value.substr(0, 6),
                        success: function(res){
                            let imgFlag = `<img src="https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/68x30/${res.brand.name}.png">`
                            spanBrand.innerHTML = imgFlag;
                            document.querySelector('input[name=card_brand]').value = res.brand.name;
                            getInstallments(amountTransaction, res.brand.name);
                        },
                        error: function(res){
                            console.log(res)
                        },
                        complete: function(res){
                            // console.log('Complete: ', res)
                        }
                    });
                }
            });

            let submitButton = document.querySelector('button.processCheckout');

            submitButton.addEventListener('click', function(event){
                event.preventDefault();

                PagSeguroDirectPayment.createCardToken({
                    cardNumber: document.querySelector('input[name=card_number]').value,
                    brand:      document.querySelector('input[name=card_brand]').value,
                    cvv:        document.querySelector('input[name=card_cvv]').value,
                    expirationMonth: document.querySelector('input[name=card_month]').value,
                    expirationYear:   document.querySelector('input[name=card_year]').value,
                    success: function(res){
                        console.log(res);
                        proccessPayment(res.card.token);
                    },
                    error: function(res){
                        console.log(res);
                    }                     

                });


            });

            function proccessPayment(token)
            {
                let data = {
                    card_token: token,
                    hash: PagSeguroDirectPayment.getSenderHash(),
                    installment: document.querySelector('select.select_installments').value,
                    card_name: document.querySelector('input[name=card_name]').value,
                    valorTotal: amountTransaction,
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    type: 'POST',
                    url: '{{ route("checkout.proccess") }}',
                    data: data,
                    dataType: 'json',
                    success: function(res){
                        console.log(res)
                    }
                });
            }



            function getInstallments(amount, brand){
                PagSeguroDirectPayment.getInstallments({
                    amount: amount,
                    brand: brand,
                    maxInstallmentNoInterest: 0,
                    success: function(res){
                        let selectInstallments = drawSelectInstallments(res.installments[brand]);
                        document.querySelector('div.installments').innerHTML = selectInstallments;  
                        console.log(res);
                    },
                    error: function(res){
                        console.log(res);
                    },
                    complete: function(res){

                    },
                });
            }

            function drawSelectInstallments(installments) {
                let select = '<label>Opções de Parcelamento:</label>';

                select += '<select class="form-control select_installments">';

                for(let l of installments) {
                    select += `<option value="${l.quantity}|${l.installmentAmount}">${l.quantity}x de ${l.installmentAmount} - Total fica ${l.totalAmount}</option>`;
                }


                select += '</select>';

                return select;
            }

        });
        
    </script>
@endsection --}}