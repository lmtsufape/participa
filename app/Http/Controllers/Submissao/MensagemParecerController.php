<?php

namespace App\Http\Controllers\Submissao;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Submissao\Evento;
use App\Models\Submissao\MensagemParecer;
use Illuminate\Support\Facades\Validator;

class MensagemParecerController extends Controller
{
    public function create(Evento $evento)
    {
        $tipo = 'evento';
        $msgpositivo = '';
        $msgnegativo = '';
        $modalidades = $evento->modalidades;
        $areas = $evento->areas;
        $msgmodpositivo = array_fill_keys($modalidades->pluck('id')->all(), '');
        $msgmodnegativo = array_fill_keys($modalidades->pluck('id')->all(), '');
        $msgareapositivo = array_fill_keys($areas->pluck('id')->all(), '');
        $msgareanegativo = array_fill_keys($areas->pluck('id')->all(), '');
        if (($msgs = $evento->mensagensParecer) && $msgs->count() > 1) {
            $msgpositivo = $msgs->where('parecer', 'positivo')->first()->texto ?? '';
            $msgnegativo = $msgs->where('parecer', 'negativo')->first()->texto ?? '';
        } else if (($msgs = $evento->areas->first()->mensagensParecer) && $msgs->count() > 1) {
            $tipo = 'area';
            foreach ($areas as $area) {
                $msgareapositivo[$area->id] = $area->mensagensParecer->where('parecer', 'positivo')->first()->texto ?? '';
                $msgareanegativo[$area->id] = $area->mensagensParecer->where('parecer', 'negativo')->first()->texto ?? '';
            }
        } else if (($msgs = $evento->modalidades->first()->mensagensParecer) && $msgs->count() > 1) {
            $tipo = 'modalidade';
            foreach ($modalidades as $modalidade) {
                $msgmodpositivo[$modalidade->id] = $modalidade->mensagensParecer->where('parecer', 'positivo')->first()->texto ?? '';
                $msgmodnegativo[$modalidade->id] = $modalidade->mensagensParecer->where('parecer', 'negativo')->first()->texto ?? '';
            }
        }
        return view('coordenador.trabalhos.mensagensParecer', compact('evento', 'tipo', 'msgpositivo', 'msgnegativo', 'msgmodpositivo', 'msgmodnegativo', 'msgareapositivo', 'msgareanegativo'));
    }

    public function store(Request $request, Evento $evento)
    {
        switch ($request->tipo) {
            case 'evento':
                $validated = Validator::make($request->all(), [
                    'msgpositivo' => 'required|string',
                    'msgnegativo' => 'required|string',
                ])->validate();
                MensagemParecer::updateOrCreate(['parecer' => 'positivo', 'evento_id' => $evento->id], ['texto' => $validated['msgpositivo']]);
                MensagemParecer::updateOrCreate(['parecer' => 'negativo', 'evento_id' => $evento->id], ['texto' => $validated['msgnegativo']]);
                MensagemParecer::whereIn('area_id', $evento->areas->pluck('id')->all())->orWhereIn('modalidade_id', $evento->modalidades->pluck('id')->all())->delete();
                return redirect()->route('coord.resultados', ['id' => $evento->id])->with('success', 'Mensagens definidas com sucesso!');
                break;
            case 'modalidade':
                $modalidades = $evento->modalidades;
                $campos = $modalidades->flatMap(function($modalidade) {
                    return [
                        'msgmodpositivo.'.$modalidade->id => 'required|string',
                        'msgmodnegativo.'.$modalidade->id => 'required|string',
                    ];
                })->all();
                $campos['msgmodpositivo'] = 'required|array';
                $campos['msgmodnegativo'] = 'required|array';
                $validated = Validator::make($request->all(), $campos)->validate();
                foreach ($modalidades as $modalidade) {
                    MensagemParecer::updateOrCreate(['parecer' => 'positivo', 'modalidade_id' => $modalidade->id], ['texto' => $validated['msgmodpositivo'][$modalidade->id]]);
                    MensagemParecer::updateOrCreate(['parecer' => 'negativo', 'modalidade_id' => $modalidade->id], ['texto' => $validated['msgmodnegativo'][$modalidade->id]]);
                }
                MensagemParecer::whereIn('area_id', $evento->areas->pluck('id')->all())->orWhere('evento_id', $evento->id)->delete();
                return redirect()->route('coord.resultados', ['id' => $evento->id])->with('success', 'Mensagens definidas com sucesso!');
                break;
            case 'area':
                    $areas = $evento->areas;
                    $campos = $areas->flatMap(function($area) {
                        return [
                            'msgareapositivo.'.$area->id => 'required|string',
                            'msgareanegativo.'.$area->id => 'required|string',
                        ];
                    })->all();
                    $campos['msgareapositivo'] = 'required|array';
                    $campos['msgareanegativo'] = 'required|array';
                    $validated = Validator::make($request->all(), $campos)->validate();
                    foreach ($areas as $area) {
                        MensagemParecer::updateOrCreate(['parecer' => 'positivo', 'area_id' => $area->id], ['texto' => $validated['msgareapositivo'][$area->id]]);
                        MensagemParecer::updateOrCreate(['parecer' => 'negativo', 'area_id' => $area->id], ['texto' => $validated['msgareanegativo'][$area->id]]);
                    }
                    MensagemParecer::WhereIn('modalidade_id', $evento->modalidades->pluck('id')->all())->orWhere('evento_id', $evento->id)->delete();
                    return redirect()->route('coord.resultados', ['id' => $evento->id])->with('success', 'Mensagens definidas com sucesso!');
                    break;
            default:
                return redirect()->route('coord.resultados', ['id' => $evento->id])->with('error', 'Falha ao definir as mensagens');
                break;
        }
    }

    public function update(Request $request, Evento $evento)
    {

    }
}
