<!DOCTYPE html>
<html>
<head>
	
</head>
<body>
    <h4>Convidamos vossa senhoria, para ser revisor do evento {{$evento->nome}}.</h4>
    
    @if (auth()->user()->id == $evento->coordenadorId)
        <h6>Att, coordenador do evento.</h6>
    @else 
        <h6>Att, comissão do evento.</h6>
    @endif
</body>
</html>