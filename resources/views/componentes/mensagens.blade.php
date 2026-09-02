<div class="col-md-8 mx-auto mt-3">
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
            <button type="button" class="btn-close text-end" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success d-flex justify-content-between">
            {{ session('success') }}
            <button type="button" class="btn-close text-end" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger d-flex justify-content-between">
            {{ session('error') }}
            <button type="button" class="btn-close text-end" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif
</div>

{{-- @if (session('success'))
  <div class="alert alert-success" role="alert" align="center">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    {{session('success')}}
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger"  role="alert" align="center">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    {{session('error')}}
  </div>
@endif --}}
