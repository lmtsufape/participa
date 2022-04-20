<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificadoRequest;
use App\Mail\EmailCertificado;
use App\Models\Inscricao\Inscricao;
use App\Models\Submissao\Assinatura;
use App\Models\Submissao\Medida;
use App\Models\Submissao\Atividade;
use App\Models\Submissao\Certificado;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Palestra;
use App\Models\Submissao\Palestrante;
use App\Models\Submissao\TipoComissao;
use App\Models\Submissao\Trabalho;
use App\Models\Users\Coautor;
use App\Models\Users\ComissaoEvento;
use App\Models\Users\Convidado;
use App\Models\Users\Revisor;
use App\Models\Users\User;
use Barryvdh\DomPDF\Facade as PDF;
use geekcom\ValidatorDocs\Rules\Certidao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $certificados = Certificado::where('evento_id', $evento->id)->get();
        return view('coordenador.certificado.index', [
            'evento'=> $evento,
            'certificados' => $certificados,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $assinaturas = Assinatura::where('evento_id', $evento->id)->get();
        return view('coordenador.certificado.create', [
            'evento'=> $evento,
            'assinaturas' => $assinaturas,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CertificadoRequest $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $validated = $request->validated();
        $certificado = new Certificado();
        $certificado->setAtributes($validated);
        $certificado->evento_id = $evento->id;

        $imagem = $request->fotoCertificado;
        $path = 'certificados/'.$evento->id.'/';
        $nome = $imagem->getClientOriginalName();
        $nomeSemEspaco = str_replace(' ', '', $nome);
        Storage::putFileAs('public/'.$path, $imagem, $nomeSemEspaco);
        $certificado->caminho = $path . $nomeSemEspaco;
        $certificado->save();

        foreach($request->assinaturas as $assinatura_id){
            $certificado->assinaturas()->attach(Assinatura::find($assinatura_id));
        }

        return redirect(route('coord.listarCertificados', ['eventoId' => $evento->id]))->with(['success' => 'Certificado cadastrado com sucesso.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Submissao\Certificado  $certificado
     * @return \Illuminate\Http\Response
     */
    public function show(Certificado $certificado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Submissao\Certificado  $certificado
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $certificado = Certificado::find($id);
        $assinaturas = Assinatura::where('evento_id', $evento->id)->get();
        return view('coordenador.certificado.edit', [
            'assinaturas' => $assinaturas,
            'certificado' => $certificado,
            'evento'=> $evento,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Submissao\Certificado  $certificado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $validatedData = $request->validate([
            'local'              => 'required|string|min:3|max:40',
            'nome'              => 'required|string|min:5|max:290',
            'texto'              => 'required|string|min:5|max:500',
            'assinaturas' => 'required',
            'data' => 'required|date',
            'tipo' => 'required',
            'tipo_comissao_id' => 'required_if:tipo,8|exclude_unless:tipo,8'
        ]);
        $certificado = Certificado::find($id);
        $certificado->setAtributes($validatedData);

        if($request->fotoCertificado != null){
            $validatedData = $request->validate([
                'fotoCertificado'  => 'required|file|mimes:png,jpeg,jpg|max:2048',
            ]);
            if(Storage::disk()->exists('public/'.$certificado->caminho)) {
                Storage::delete('storage/'.$certificado->caminho);
            }

            $imagem = $request->fotoCertificado;
            $path = 'certificados/'.$evento->id.'/';
            $nome = $imagem->getClientOriginalName();
            $nomeSemEspaco = str_replace(' ', '', $nome);
            Storage::putFileAs('public/'.$path, $imagem, $nomeSemEspaco);
            $certificado->caminho = $path . $nomeSemEspaco;

        }

        foreach($request->assinaturas as $assinatura_id){
            if ($certificado->assinaturas()->where('assinatura_id', $assinatura_id)->first() == null) {
                $certificado->assinaturas()->attach(Assinatura::find($assinatura_id));
            }
        }

        foreach ($certificado->assinaturas as $assinatura) {
            if (!(in_array($assinatura->id, $request->assinaturas))) {
                $certificado->assinaturas()->detach($assinatura->id);
            }
        }

        $certificado->update();

        return redirect(route('coord.listarCertificados', ['eventoId' => $evento->id]))->with(['success' => 'Certificado editado com sucesso.']);
    }

    public function salvarMedida(Request $request)
    {
        $certificado = Certificado::find($request->certificado_id);
        $assinaturas = $certificado->assinaturas;
        $assinaturas_id = $assinaturas->pluck('id');
        $medida = Medida::firstOrNew(['certificado_id' => $certificado->id, 'tipo' => Medida::TIPO_ENUM['texto']]);
        $medida->x = $request['texto-x'];
        $medida->y = $request['texto-y'];
        $medida->largura = $request['texto-largura'];
        $medida->fontSize = intval($request['texto-fontSize']);
        $medida->save();

        $medida = Medida::firstOrNew(['certificado_id' => $certificado->id, 'tipo' => Medida::TIPO_ENUM['data']]);
        $medida->x = $request['data-x'];
        $medida->y = $request['data-y'];
        $medida->largura = $request['data-largura'];
        $medida->fontSize = intval($request['data-fontSize']);
        $medida->save();

        foreach ($assinaturas_id as $id) {
            $medida = Medida::firstOrNew(['certificado_id' => $certificado->id, 'tipo' => Medida::TIPO_ENUM['cargo_assinatura'], 'assinatura_id' => $id]);
            $medida->x = $request['cargo-x-'.$id];
            $medida->y = $request['cargo-y-'.$id];
            $medida->largura = $request['cargo-largura-'.$id];
            $medida->fontSize = intval($request['cargo-fontSize-'.$id]);
            $medida->save();

            $medida = Medida::firstOrNew(['certificado_id' => $certificado->id, 'tipo' => Medida::TIPO_ENUM['nome_assinatura'], 'assinatura_id' => $id]);
            $medida->x = $request['nome-x-'.$id];
            $medida->y = $request['nome-y-'.$id];
            $medida->largura = $request['nome-largura-'.$id];
            $medida->fontSize = intval($request['nome-fontSize-'.$id]);
            $medida->save();

            $medida = Medida::firstOrNew(['certificado_id' => $certificado->id, 'tipo' => Medida::TIPO_ENUM['imagem_assinatura'], 'assinatura_id' => $id]);
            $medida->x = $request['imagem-x-'.$id];
            $medida->y = $request['imagem-y-'.$id];
            $medida->largura = $request['imagem-largura-'.$id];
            $medida->altura = $request['imagem-altura-'.$id];
            $medida->save();
        }
        return redirect()->route('coord.listarCertificados', ['eventoId' => $certificado->evento->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Submissao\Certificado  $certificado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $certificado = Certificado::find($id);
        $evento = $certificado->evento;
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        foreach($certificado->assinaturas()->get() as $modelo){
            $modelo->pivot->delete();
        }
        $certificado->delete();
        return redirect(route('coord.listarCertificados', ['eventoId' => $evento->id]))->with(['success' => 'Certificado deletado com sucesso.']);
    }

    public function modelo($id)
    {
        $certificado = Certificado::find($id);
        $medidas = $certificado->medidas()->with('assinatura')->get();
        $evento = $certificado->evento;
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Recife');
        $dataHoje = strftime('%d de %B de %Y', strtotime($certificado->data));
        return view('coordenador.certificado.modelo', compact('certificado', 'dataHoje', 'evento', 'medidas'));
    }

    public function previewCertificado($certificadoId, $destinatarioId, $trabalhoId)
    {
        $certificado = Certificado::find($certificadoId);
        $evento = $certificado->evento;
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        return $this->gerar_pdf_certificado($certificado, $destinatarioId, $trabalhoId, $evento);
    }

    private function gerar_pdf_certificado($certificado, $destinatarioId, $trabalhoId, $evento)
    {
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Recife');
        $validacao = DB::table('certificado_user')->where([
            ['certificado_id', '=', $certificado->id],
            ['user_id', '=', $destinatarioId],
        ])->first()->validacao;
        switch ($certificado->tipo) {
            case Certificado::TIPO_ENUM['apresentador']:
                $validacao = DB::table('certificado_user')->where([
                    ['certificado_id', '=', $certificado->id],
                    ['user_id', '=', $destinatarioId],
                    ['trabalho_id', '=', $trabalhoId],
                ])->first()->validacao;
                break;
            case Certificado::TIPO_ENUM['expositor']:
                $validacao = DB::table('certificado_user')->where([
                    ['certificado_id', '=', $certificado->id],
                    ['user_id', '=', $destinatarioId],
                    ['palestra_id', '=', $trabalhoId],
                ])->first()->validacao;
                break;
            case Certificado::TIPO_ENUM['outras_comissoes']:
                $validacao = DB::table('certificado_user')->where([
                    ['certificado_id', '=', $certificado->id],
                    ['user_id', '=', $destinatarioId],
                    ['comissao_id', '=', $trabalhoId],
                ])->first()->validacao;
                break;
            default:
                break;
        }
        $qrcode = base64_encode(QrCode::generate($validacao));
        switch($certificado->tipo){
            case(Certificado::TIPO_ENUM['apresentador']):
                    $user = User::find($destinatarioId);
                    $trabalho = Trabalho::find($trabalhoId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['qrcode' => $qrcode, 'validacao' => $validacao, 'certificado' => $certificado, 'user' => $user, 'trabalho' => $trabalho, 'cargo' => 'Apresentador', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                break;
            case(Certificado::TIPO_ENUM['comissao_cientifica']):
                    $user = User::find($destinatarioId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['qrcode' => $qrcode, 'validacao' => $validacao, 'certificado' => $certificado, 'user' => $user, 'cargo' => 'Comissão Científica', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                    break;
            case(Certificado::TIPO_ENUM['comissao_organizadora']):
                    $user = User::find($destinatarioId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['qrcode' => $qrcode, 'validacao' => $validacao, 'certificado' => $certificado, 'user' => $user, 'cargo' => 'Comissão Organizadora', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                    break;
            case(Certificado::TIPO_ENUM['revisor']):
                    $user = User::find($destinatarioId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['qrcode' => $qrcode, 'validacao' => $validacao, 'certificado' => $certificado, 'user' => $user, 'cargo' => 'Revisor', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                    break;
            case(Certificado::TIPO_ENUM['participante']):
                    $user = User::find($destinatarioId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['qrcode' => $qrcode, 'validacao' => $validacao, 'certificado' => $certificado, 'user' => $user, 'cargo' => 'Participante', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                    break;
            case(Certificado::TIPO_ENUM['expositor']):
                    $user = Palestrante::find($destinatarioId);
                    $palestra = Palestra::find($trabalhoId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['qrcode' => $qrcode, 'validacao' => $validacao, 'certificado' => $certificado, 'user' => $user, 'palestra' => $palestra, 'cargo' => 'Expositor', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                    break;
            case(Certificado::TIPO_ENUM['coordenador_comissao_cientifica']):
                $user = User::find($destinatarioId);
                $trabalho = Trabalho::find($trabalhoId);
                $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['qrcode' => $qrcode, 'validacao' => $validacao, 'certificado' => $certificado, 'user' => $user, 'trabalho' => $trabalho, 'cargo' => 'Coordenador comissão científica', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                break;
            case(Certificado::TIPO_ENUM['outras_comissoes']):
                $user = User::find($destinatarioId);
                $comissao = TipoComissao::find($trabalhoId);
                $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['qrcode' => $qrcode, 'validacao' => $validacao, 'certificado' => $certificado, 'user' => $user, 'comissao' => $comissao, 'cargo' => "membro da comissao {$comissao->nome}", 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                break;
        }
        return $pdf->stream('preview.pdf');
    }

    public function emitir(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $certificados = Certificado::where('evento_id', $evento->id)->get();
        $destinatarios = array('Apresentadores', 'Coordenador da comissão científica', 'Membro da comissão científica', 'Membro da comissão organizadora', 'Palestrante', 'Participantes', 'Revisores', 'Membro de outra comissão');
        return view('coordenador.certificado.emissao', [
            'evento'=> $evento,
            'certificados' => $certificados,
            'destinatarios' => $destinatarios,
        ]);
    }

    public function ajaxDestinatarios(Request $request)
    {

       if($request->destinatario == Certificado::TIPO_ENUM['apresentador']){
            $destinatarios = collect();
            $trab = Trabalho::where('eventoId', '=', $request->eventoId)->orderBy('titulo')->get();
            $trabalhos = collect();
            foreach($trab as $trabalho){
                $destinatarios->push($trabalho->autor);
                $trabalhos->push($trabalho);
                foreach($trabalho->coautors as $coautor){
                    $destinatarios->push($coautor->user);
                    $trabalhos->push($trabalho);
                }
            }
        }elseif($request->destinatario == Certificado::TIPO_ENUM['comissao_cientifica'] || $request->destinatario == Certificado::TIPO_ENUM['coordenador_comissao_cientifica']){
            $destinatarios = User::join('comissao_cientifica_eventos', 'users.id', '=', 'comissao_cientifica_eventos.user_id')->where('comissao_cientifica_eventos.evento_id', '=', $request->eventoId)->selectRaw('DISTINCT users.*')->get()->sortBy(
                function($membro) {
                    return $membro->name;
                },
                SORT_REGULAR);
        }elseif($request->destinatario == Certificado::TIPO_ENUM['comissao_organizadora']){
            $destinatarios = User::join('comissao_organizadora_eventos', 'users.id', '=', 'comissao_organizadora_eventos.user_id')->where('comissao_organizadora_eventos.evento_id', '=', $request->eventoId)->selectRaw('DISTINCT users.*')->get()->sortBy(
                function($membro) {
                    return $membro->name;
                },
                SORT_REGULAR);
        }elseif($request->destinatario == Certificado::TIPO_ENUM['revisor']){
            $destinatarios = User::join('revisors', 'users.id', '=', 'revisors.user_id')->where('revisors.evento_id', '=', $request->eventoId)->selectRaw('DISTINCT users.*')->get()->sortBy(
                function($revisor) {
                    return $revisor->name;
                },
                SORT_REGULAR);
        }elseif($request->destinatario == Certificado::TIPO_ENUM['participante']){

            $autores = Trabalho::where('eventoId', $request->eventoId)->get()->pluck('autor');

            $cientifica = Evento::find($request->eventoId)->usuariosDaComissao;

            $organizadora = Evento::find($request->eventoId)->usuariosDaComissaoOrganizadora;

            $revisores = Revisor::where('evento_id', $request->eventoId)->get()->pluck('user');

            $coautores = Coautor::where('eventos_id', $request->eventoId)->get()->pluck('user');

            $inscritos = Inscricao::where('evento_id', $request->eventoId)->get()->pluck('user');

            $destinatarios = $autores->merge($cientifica)
                ->merge($organizadora)
                ->merge($revisores)
                ->merge($coautores)
                ->merge($inscritos)
                ->sortBy('name');
        }elseif($request->destinatario == Certificado::TIPO_ENUM['expositor']){
            $destinatarios = Evento::find($request->eventoId)->palestrantes;
            $palestras = $destinatarios->map(function($destinatario){ return $destinatario->palestra;});
        }elseif($request->destinatario == Certificado::TIPO_ENUM['outras_comissoes']){
            $comissao = TipoComissao::find($request->tipo_comissao_id);
            $destinatarios = $comissao->membros;
        }
        $desti = collect();

        foreach($destinatarios as $dest){
            $desti->push($dest);
        }

        switch ($request->destinatario) {
            case Certificado::TIPO_ENUM['apresentador']:
                $modeloCertificado = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['apresentador']]])->first();
                $certificados = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['apresentador']]])->get();
                break;

            case Certificado::TIPO_ENUM['comissao_cientifica']:
                $modeloCertificado = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['comissao_cientifica']]])->first();
                $certificados = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['comissao_cientifica']]])->get();
                break;

            case Certificado::TIPO_ENUM['comissao_organizadora']:
                $modeloCertificado = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['comissao_organizadora']]])->first();
                $certificados = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['comissao_organizadora']]])->get();
                break;

            case Certificado::TIPO_ENUM['revisor']:
                $modeloCertificado = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['revisor']]])->first();
                $certificados = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['revisor']]])->get();
                break;

            case Certificado::TIPO_ENUM['participante']:
                $modeloCertificado = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['participante']]])->first();
                $certificados = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['participante']]])->get();
                break;

            case Certificado::TIPO_ENUM['expositor']:
                $modeloCertificado = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['expositor']]])->first();
                $certificados = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['expositor']]])->get();
                break;
            case Certificado::TIPO_ENUM['coordenador_comissao_cientifica']:
                $modeloCertificado = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['coordenador_comissao_cientifica']]])->first();
                $certificados = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['coordenador_comissao_cientifica']]])->get();
                break;
            case Certificado::TIPO_ENUM['outras_comissoes']:
                $modeloCertificado = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['outras_comissoes']], ['tipo_comissao_id', $request->tipo_comissao_id]])->first();
                $certificados = Certificado::where([['evento_id', $request->eventoId], ['tipo', Certificado::TIPO_ENUM['outras_comissoes']], ['tipo_comissao_id', $request->tipo_comissao_id]])->get();
                break;

            default:
                break;
        }


        if($request->destinatario == Certificado::TIPO_ENUM['apresentador']){
            $data = array(
                'success'   => true,
                'destinatarios'     => $desti,
                'trabalhos' => $trabalhos,
                'certificado' => $modeloCertificado,
                'certificados' => $certificados,
            );
            echo json_encode($data);
        }elseif($request->destinatario == Certificado::TIPO_ENUM['expositor']){
            $data = array(
                'success'   => true,
                'destinatarios'     => $desti,
                'palestras' => $palestras,
                'certificado' => $modeloCertificado,
                'certificados' => $certificados,
            );
            echo json_encode($data);
        }elseif($request->destinatario == Certificado::TIPO_ENUM['outras_comissoes']){
            $data = array(
                'success'   => true,
                'destinatarios' => $desti,
                'comissao' => $comissao,
                'certificado' => $modeloCertificado,
                'certificados' => $certificados,
            );
            echo json_encode($data);
        }else{
            $data = array(
                'success'   => true,
                'destinatarios'     => $desti,
                'certificado' => $modeloCertificado,
                'certificados' => $certificados,
            );
            echo json_encode($data);
        }
    }

    public function enviarCertificacao(Request $request)
    {
        $request->validate(['certificado' => 'required']);
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $certificado = Certificado::find($request->certificado);
        $validacoes = collect($request->destinatarios)->map(function($item){return Hash::make($item);});
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Recife');
        switch($request->destinatario){
            case(Certificado::TIPO_ENUM['apresentador']):
                foreach($request->destinatarios as $i => $destinarioId){
                    $certificado->usuarios()->attach($destinarioId, ['validacao' => $validacoes[$i], 'trabalho_id' => $request->trabalhos[$i]]);
                    $user = User::find($destinarioId);
                    $trabalho = Trabalho::find($request->trabalhos[$i]);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['certificado' => $certificado, 'user' => $user, 'trabalho' => $trabalho, 'cargo' => 'Apresentador', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                    Mail::to($user->email)->send(new EmailCertificado($user, 'apresentador de trabalho', $evento->nome, $pdf));
                }
                break;
            case(Certificado::TIPO_ENUM['comissao_cientifica']):
                foreach($request->destinatarios as $i => $destinarioId){
                    $certificado->usuarios()->attach($destinarioId, ['validacao' => $validacoes[$i]]);
                    $user = User::find($destinarioId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['certificado' => $certificado, 'user' => $user, 'cargo' => 'Comissão Científica', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                    Mail::to($user->email)->send(new EmailCertificado($user, 'membro da Comissão Científica', $evento->nome, $pdf));
                }
                break;
            case(Certificado::TIPO_ENUM['comissao_organizadora']):
                foreach($request->destinatarios as $i => $destinarioId){
                    $certificado->usuarios()->attach($destinarioId, ['validacao' => $validacoes[$i]]);
                    $user = User::find($destinarioId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['certificado' => $certificado, 'user' => $user, 'cargo' => 'Comissão Organizadora', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                    Mail::to($user->email)->send(new EmailCertificado($user, 'membro da Comissão Organizadora', $evento->nome, $pdf));
                }
                break;
            case(Certificado::TIPO_ENUM['revisor']):
                foreach($request->destinatarios as $i => $destinarioId){
                    $certificado->usuarios()->attach($destinarioId, ['validacao' => $validacoes[$i]]);
                    $user = User::find($destinarioId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['certificado' => $certificado, 'user' => $user, 'cargo' => 'Revisor', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                    Mail::to($user->email)->send(new EmailCertificado($user, 'avaliador/a', $evento->nome, $pdf));
                }
                break;
            case(Certificado::TIPO_ENUM['participante']):
                foreach($request->destinatarios as $i => $destinarioId){
                    $certificado->usuarios()->attach($destinarioId, ['validacao' => $validacoes[$i]]);
                    $user = User::find($destinarioId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['certificado' => $certificado, 'user' => $user, 'cargo' => 'Participante', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                    Mail::to($user->email)->send(new EmailCertificado($user, 'participante', $evento->nome, $pdf));
                }
                break;
            case(Certificado::TIPO_ENUM['expositor']):
                foreach($request->destinatarios as $i => $destinarioId){
                    $certificado->usuarios()->attach($destinarioId, ['validacao' => $validacoes[$i], 'palestra_id' => $request->palestras[$i]]);
                    $user = Palestrante::find($destinarioId);
                    $palestra = Palestra::find($request->palestras[$i]);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['certificado' => $certificado, 'user' => $user, 'palestra' => $palestra, 'cargo' => 'Expositor', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                    Mail::to($user->email)->send(new EmailCertificado($user, 'palestrante', $evento->nome, $pdf));
                }
                break;
            case(Certificado::TIPO_ENUM['coordenador_comissao_cientifica']):
                foreach($request->destinatarios as $i => $destinarioId){
                    $certificado->usuarios()->attach($destinarioId, ['validacao' => $validacoes[$i]]);
                    $user = User::find($destinarioId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['certificado' => $certificado, 'user' => $user, 'cargo' => 'Coordenador comissão científica', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                    Mail::to($user->email)->send(new EmailCertificado($user, 'coordenador/a da comissão Científica', $evento->nome, $pdf));
                }
                break;
            case(Certificado::TIPO_ENUM['outras_comissoes']):
                foreach($request->destinatarios as $i => $destinarioId){
                    $certificado->usuarios()->attach($destinarioId, ['validacao' => $validacoes[$i], 'comissao_id' => $request->tipo_comissao_id]);
                    $user = User::find($destinarioId);
                    $comissao = TipoComissao::find($request->tipo_comissao_id);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['certificado' => $certificado, 'user' => $user, 'cargo' => "membro da comissão {$comissao->nome}", 'evento' => $evento, 'comissao' => $comissao, 'dataHoje' => strftime('%d de %B de %Y', strtotime($certificado->data))])->setPaper('a4', 'landscape');
                    Mail::to($user->email)->send(new EmailCertificado($user, "membro da comissão {$comissao->nome}", $evento->nome, $pdf));
                }
                break;
        }
        return redirect(route('coord.emitirCertificado', ['eventoId' => $evento->id]))->with(['success' => 'Certificados enviados com sucesso.']);
    }

    public function listarEmissoes(Certificado $certificado)
    {
        $evento = $certificado->evento;
        $usuarios = $certificado->usuarios;
        $comissao = null;
        $palestras = null;
        $trabalhos = null;
        $tipos = Certificado::TIPO_ENUM;
        switch ($certificado->tipo) {
            case Certificado::TIPO_ENUM['apresentador']:
                $trabalhos = Trabalho::find($certificado->usuarios->pluck('pivot.trabalho_id'));
                break;
            case Certificado::TIPO_ENUM['expositor']:
                $palestras = Palestra::find($certificado->usuarios->pluck('pivot.palestra_id'));
                break;
            case Certificado::TIPO_ENUM['outras_comissoes']:
                $usuario = $certificado->usuarios->first();
                if($usuario)
                    $comissao = TipoComissao::find($usuario->pivot->comissao_id);
                break;
            default:
                break;
        }
        return view('coordenador.certificado.emissoes', compact('evento', 'usuarios', 'certificado', 'comissao', 'palestras', 'trabalhos', 'tipos'));
    }

    public function validar(Request $request)
    {
        $certificado_user = DB::table('certificado_user')->where([
            ['validacao', '=', $request['hash']],
            ['valido', '=', true],
        ])->first();
        if($certificado_user) {
            $certificado = Certificado::find($certificado_user->certificado_id);
            $evento = $certificado->evento;
            switch ($certificado->tipo) {
                case Certificado::TIPO_ENUM['apresentador']:
                    return $this->gerar_pdf_certificado($certificado, $certificado_user->user_id, $certificado_user->trabalho_id, $evento);
                case Certificado::TIPO_ENUM['expositor']:
                    return $this->gerar_pdf_certificado($certificado, $certificado_user->user_id, $certificado_user->palestra_id, $evento);
                case Certificado::TIPO_ENUM['outras_comissoes']:
                    return $this->gerar_pdf_certificado($certificado, $certificado_user->user_id, $certificado_user->comissao_id, $evento);
                default:
                    return $this->gerar_pdf_certificado($certificado, $certificado_user->user_id, 0, $evento);
            }
        } else {
            return redirect()->back()->with('message', 'Esse código de verificação é inválido');
        }
    }
}
