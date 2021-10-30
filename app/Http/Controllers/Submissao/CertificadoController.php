<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificadoRequest;
use App\Mail\EmailCertificado;
use App\Models\Submissao\Assinatura;
use App\Models\Submissao\Certificado;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Trabalho;
use App\Models\Users\Revisor;
use App\Models\Users\User;
use Barryvdh\DomPDF\Facade as PDF;
use geekcom\ValidatorDocs\Rules\Certidao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
        $this->authorize('isCoordenadorOrComissao', $evento);
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
        $this->authorize('isCoordenadorOrComissao', $evento);
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
        $this->authorize('isCoordenadorOrComissao', $evento);
        $request->validated();
        $certificado = new Certificado();
        $certificado->setAtributes($request);
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
        $this->authorize('isCoordenadorOrComissao', $evento);
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
        $this->authorize('isCoordenadorOrComissao', $evento);
        $validatedData = $request->validate([
            'local'              => 'required|string|min:3|max:40',
            'nome'              => 'required|string|min:5|max:290',
            'texto'              => 'required|string|min:5|max:500',
            'assinaturas' => 'required',
        ]);
        $certificado = Certificado::find($id);
        $certificado->setAtributes($request);

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
        $this->authorize('isCoordenadorOrComissao', $evento);
        foreach($certificado->assinaturas()->get() as $modelo){
            $modelo->pivot->delete();
        }
        $certificado->delete();
        return redirect(route('coord.listarCertificados', ['eventoId' => $evento->id]))->with(['success' => 'Certificado deletado com sucesso.']);
    }

    public function modelo($id)
    {
        $certificado = Certificado::find($id);
        $evento = $certificado->evento;
        $this->authorize('isCoordenadorOrComissao', $evento);
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Recife');
        $pdf = PDF::loadView('coordenador.certificado.modelo', ['certificado' => $certificado, 'dataHoje' => strftime('%d de %B de %Y', strtotime('today'))])->setPaper('a4', 'landscape');

        return $pdf->stream('modelo.pdf');
    }

    public function emitir(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrComissao', $evento);
        $certificados = Certificado::where('evento_id', $evento->id)->get();
        $destinatarios = array('Apresentadores', 'Comissão científica', 'Comissão organizadora', 'Expositor', 'Participantes', 'Revisores');
        return view('coordenador.certificado.emissao', [
            'evento'=> $evento,
            'certificados' => $certificados,
            'destinatarios' => $destinatarios,
        ]);
    }

    public function ajaxDestinatarios(Request $request)
    {

       if($request->destinatario == '1' || $request->destinatario == '6'){
            $destinatarios = User::join('trabalhos', 'users.id', '=', 'trabalhos.autorId')->where('trabalhos.eventoId', '=', $request->eventoId)->selectRaw('DISTINCT users.*')->get()->sortBy(
                function($autor) {
                    return $autor->name;
                },
                SORT_REGULAR);
            $coautores = User::join('coautors', 'users.id', '=', 'coautors.autorId')->where('coautors.eventos_id', '=', $request->eventoId)->selectRaw('DISTINCT users.*')->get()->sortBy(
                function($coautor) {
                    return $coautor->name;
                },
                SORT_REGULAR);

            foreach($coautores as $dest){
                if(!$destinatarios->contains($dest)){
                    $destinatarios->push($dest);
                }
            }
            $destinatarios = $destinatarios->sortBy('name');
        }elseif($request->destinatario == '2'){
            $destinatarios = User::join('comissao_cientifica_eventos', 'users.id', '=', 'comissao_cientifica_eventos.user_id')->where('comissao_cientifica_eventos.evento_id', '=', $request->eventoId)->selectRaw('DISTINCT users.*')->get()->sortBy(
                function($membro) {
                    return $membro->name;
                },
                SORT_REGULAR);
        }elseif($request->destinatario == '3'){
            $destinatarios = User::join('comissao_organizadora_eventos', 'users.id', '=', 'comissao_organizadora_eventos.user_id')->where('comissao_organizadora_eventos.evento_id', '=', $request->eventoId)->selectRaw('DISTINCT users.*')->get()->sortBy(
                function($membro) {
                    return $membro->name;
                },
                SORT_REGULAR);
        }elseif($request->destinatario == '4'){
            $destinatarios = User::join('revisors', 'users.id', '=', 'revisors.user_id')->where('revisors.evento_id', '=', $request->eventoId)->selectRaw('DISTINCT users.*')->get()->sortBy(
                function($revisor) {
                    return $revisor->name;
                },
                SORT_REGULAR);
        }elseif($request->destinatario == '5'){

            $autores = User::join('trabalhos', 'users.id', '=', 'trabalhos.autorId')->where('trabalhos.eventoId', '=', $request->eventoId)->selectRaw('DISTINCT users.*')->get()->sortBy(
                function($autor) {
                    return $autor->name;
                },
                SORT_REGULAR);

            $cientifica = User::join('comissao_cientifica_eventos', 'users.id', '=', 'comissao_cientifica_eventos.user_id')->where('comissao_cientifica_eventos.evento_id', '=', $request->eventoId)->selectRaw('DISTINCT users.*')->get()->sortBy(
                function($membro) {
                    return $membro->name;
                },
                SORT_REGULAR);

            $organizadora = User::join('comissao_organizadora_eventos', 'users.id', '=', 'comissao_organizadora_eventos.user_id')->where('comissao_organizadora_eventos.evento_id', '=', $request->eventoId)->selectRaw('DISTINCT users.*')->get()->sortBy(
                function($membro) {
                    return $membro->name;
                },
                SORT_REGULAR);

            $revisores = User::join('revisors', 'users.id', '=', 'revisors.user_id')->where('revisors.evento_id', '=', $request->eventoId)->selectRaw('DISTINCT users.*')->get()->sortBy(
                function($revisor) {
                    return $revisor->name;
                },
                SORT_REGULAR);

            $coautores = User::join('coautors', 'users.id', '=', 'coautors.autorId')->where('coautors.eventos_id', '=', $request->eventoId)->selectRaw('DISTINCT users.*')->get()->sortBy(
                function($coautor) {
                    return $coautor->name;
                },
                SORT_REGULAR);

            $destinatarios = collect();

            foreach($autores as $dest){
                $destinatarios->push($dest);
            }

            foreach($coautores as $dest){
                if(!$destinatarios->contains($dest)){
                    $destinatarios->push($dest);
                }
            }

            foreach($cientifica as $dest){
                if(!$destinatarios->contains($dest)){
                    $destinatarios->push($dest);
                }
            }
            foreach($organizadora as $dest){
                if(!$destinatarios->contains($dest)){
                    $destinatarios->push($dest);
                }
            }
            foreach($revisores as $dest){
                if(!$destinatarios->contains($dest)){
                    $destinatarios->push($dest);
                }
            }
            $destinatarios = $destinatarios->sortBy('name');
        }
        $desti = collect();

        foreach($destinatarios as $dest){
            $desti->push($dest);
        }

        $data = array(
            'success'   => true,
            'destinatarios'     => $desti,
        );
        echo json_encode($data);
    }

    public function enviarCertificacao(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrComissao', $evento);
        $certificado = Certificado::find($request->certificado);
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Recife');
        switch($request->destinatario){
            case(1):
                foreach($request->destinatarios as $destinarioId){
                    $user = User::find($destinarioId);
                    $trabalhos = Trabalho::where([['autorId', $user->id], ['eventoId', $evento->id]])->get();
                    foreach($trabalhos as $trabalho){
                        $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['certificado' => $certificado, 'user' => $user, 'trabalho' => $trabalho, 'cargo' => 'Apresentador', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime('today'))])->setPaper('a4', 'landscape');
                        Mail::to($user->email)->send(new EmailCertificado($user, 'apresentador de trabalho', $evento->nome, $pdf));
                    }
                }
                break;
            case(2):
                foreach($request->destinatarios as $destinarioId){
                    $user = User::find($destinarioId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['certificado' => $certificado, 'user' => $user, 'cargo' => 'Comissão Científica', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime('today'))])->setPaper('a4', 'landscape');
                    Mail::to($user->email)->send(new EmailCertificado($user, 'Comissão Científica', $evento->nome, $pdf));
                }
                break;
            case(3):
                foreach($request->destinatarios as $destinarioId){
                    $user = User::find($destinarioId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['certificado' => $certificado, 'user' => $user, 'cargo' => 'Comissão Organizadora', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime('today'))])->setPaper('a4', 'landscape');
                    Mail::to($user->email)->send(new EmailCertificado($user, 'Comissão Científica', $evento->nome, $pdf));
                }
                break;
            case(4):
                foreach($request->destinatarios as $destinarioId){
                    $user = User::find($destinarioId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['certificado' => $certificado, 'user' => $user, 'cargo' => 'Revisor', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime('today'))])->setPaper('a4', 'landscape');
                    Mail::to($user->email)->send(new EmailCertificado($user, 'revisor', $evento->nome, $pdf));
                }
                break;
            case(5):
                foreach($request->destinatarios as $destinarioId){
                    $user = User::find($destinarioId);
                    $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['certificado' => $certificado, 'user' => $user, 'cargo' => 'Participante', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime('today'))])->setPaper('a4', 'landscape');
                    Mail::to($user->email)->send(new EmailCertificado($user, 'participante', $evento->nome, $pdf));
                }
                break;
            case(6):
                foreach($request->destinatarios as $destinarioId){
                    $user = User::find($destinarioId);
                    $trabalhos = Trabalho::where([['autorId', $user->id], ['eventoId', $evento->id]])->get();
                    foreach($trabalhos as $trabalho){
                        $pdf = PDF::loadView('coordenador.certificado.certificado_preenchivel', ['certificado' => $certificado, 'user' => $user, 'trabalho' => $trabalho, 'cargo' => 'Expositor', 'evento' => $evento, 'dataHoje' => strftime('%d de %B de %Y', strtotime('today'))])->setPaper('a4', 'landscape');
                        Mail::to($user->email)->send(new EmailCertificado($user, 'expositor(a)', $evento->nome, $pdf));
                    }
                }
                break;
        }
        return redirect(route('coord.emitirCertificado', ['eventoId' => $evento->id]))->with(['success' => 'Certificados enviados com sucesso.']);
    }

}
