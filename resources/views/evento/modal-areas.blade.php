<!-- Modal Todas as Áreas -->
<div class="modal fade" id="modalTodasAreas" tabindex="-1" aria-labelledby="modalTodasAreasLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" >
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="modalTodasAreasLabel">{{ __('Todas as áreas temáticas') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Fechar') }}"></button>
            </div>
            <div class="modal-body">
            <div class="row">
                    <div class="col-6 ">
                        <ul>
                            @foreach($areas->slice(0, ceil($areas->count()/2)) as $area)
                                <li>{{ $area->nome }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-6">
                        <ul>
                            @foreach($areas->slice(ceil($areas->count()/2)) as $area)
                                <li>{{ $area->nome }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Fechar') }}</button>
            </div>
        </div>
    </div>
</div> 