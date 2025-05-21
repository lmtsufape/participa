@props(['evento'])
<form action="{{ route('evento.deletar', $evento->id) }}" method="post">
    @csrf
    @method('DELETE')
    <div class="modal fade" id="modalExcluirEvento{{ $evento->id }}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #114048ff; color: white;">
                    <h5 class="modal-title" id="#label">{{__("Confirmação")}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{__("Tem certeza de deseja excluir esse evento")}}?
                    <hr>
                    <div class="form-group">
                        <label for="email">{{ __('Email do criador do evento') }}</label>
                        <input type="email" class="form-control @error('email'.$evento->id) is-invalid @enderror" id="email" name="email{{$evento->id}}" placeholder="name@example.com" value="{{old('email'.$evento->id)}}">
                    </div>
                    @error('email'.$evento->id)
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{__("Não")}}</button>
                    <button type="submit" class="btn btn-primary">
                        {{__("Sim")}}</button>
                </div>
            </div>
        </div>
    </div>
</form>

@section('javascript')
@parent
@if($errors->has('email'.$evento->id))
    <script>
        $(function() {
            $('#modalExcluirEvento{{$evento->id}}').modal('show');
        });
    </script>
@endif
@endsection
