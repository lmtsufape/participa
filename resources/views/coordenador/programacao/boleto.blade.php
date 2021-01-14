@extends('layouts.app')


@section('content')

    <div class="container">
            <div class="row justify-content-center titulo">
                <div class="col-sm-12">
                    <h1>Dados para Boleto</h1>
                </div>
            </div>           
        <div class="col-md-6"> 
            <button onclick="sl();">GERAR BOLETO</button>

            <a id="link" target="_blank" href="#" style="display: none">Clique aqui para abir o Boleto</a>
            
        </div>
    </div>


@endsection

@section('javascript')
    
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script> 

    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script>
            function sl() {
                let amountTransaction = '{{ $total }}';
                let data = {
                    hash: PagSeguroDirectPayment.getSenderHash(),
                    user_id: '{{ $data['user_id'] }}',
                    evento_id: '{{ $data['evento_id'] }}',
                    promocao_id: '{{ $data['promocao_id'] ?? null }}',
                    metodo: '{{ $data['metodo'] }}',
                    cupom: '{{ $data['cupom'] ?? null }}',
                    valorTotal: amountTransaction,

                    _token: '{{ csrf_token() }}'
                };
                

                

                $.ajax({
                    type: 'post',
                    url: '{{ route("checkout.boleto") }}',
                    data: data,
                    dataType: 'json',
                    success: function(res){ 
                        console.log(res.data.pagseguro);                       
                        document.getElementById('link').style.display="block";
                        document.getElementById('link').href =res.data.pagseguro.paymentLink;
                        
                    }
                });
                // $('#pagseguro_token').val(PagSeguroDirectPayment.getSenderHash())

                
            }
        
    </script>  
    
    

    <script type="text/javascript">
        
    </script>
@endsection