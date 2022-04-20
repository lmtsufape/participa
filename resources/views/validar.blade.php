<form action="{{route('validarCertificadoPost')}}" method="POST">
    @csrf
    <input type="text" name="hash">
    <button type="submit">Submit</button>
</form>
@if(session('message'))
  <div class="alert alert-danger"  role="alert" align="center" style="position:absolute width:100%">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    {{session('message')}}
  </div>
@endif
<img src="data:image/png;base64, {!! base64_encode(QRCode::text('aaaa')->png() !!}">
