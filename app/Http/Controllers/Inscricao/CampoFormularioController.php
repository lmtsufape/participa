<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use App\Models\Inscricao\CampoFormulario;
use App\Models\Inscricao\CampoFormularioSelect;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Submissao\Evento;
use App\Support\RegistrationFormFields;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CampoFormularioController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $evento = Evento::findOrFail($request->evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $validated = $request->validate($this->rules($request));

        if (!$evento->categoriasParticipantes()->exists()) {
            return redirect()
                ->back()
                ->withErrors(['erroCategoria' => 'É necessário criar uma categoria antes de cadastrar campos do formulário.'])
                ->withInput($validated);
        }

        if (!$request->boolean('para_todas') && $request->categoria === null) {
            return redirect()
                ->back()
                ->withErrors(['erroCategoria' => 'Escolha as categorias em que o campo será exibido.'])
                ->withInput($validated);
        }

        $opcoes = $this->normalizarOpcoes($request->input('select_text', []));

        if ($request->tipo_campo === 'select' && count($opcoes) < 2) {
            return redirect()
                ->back()
                ->withErrors(['select_text' => 'Informe pelo menos duas opções para o campo de seleção.'])
                ->withInput($validated);
        }

        DB::transaction(function () use ($request, $evento, $opcoes) {
            $campo = CampoFormulario::create([
                'titulo' => $request->titulo_do_campo,
                'tipo' => $request->tipo_campo,
                'evento_id' => $evento->id,
                'obrigatorio' => $request->boolean('campo_obrigatorio'),
            ]);

            $this->sincronizarOpcoes($campo, $opcoes);
            $this->sincronizarCategorias($campo, $evento, $request);
        });

        return redirect()->back()->with(['success' => 'Campo salvo com sucesso!']);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($request->evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        $campo = CampoFormulario::withCount('inscricoesFeitas')->findOrFail($id);
        abort_if($campo->evento_id !== $evento->id, 404);

        $validated = $request->validate($this->rules($request));

        if (!$request->boolean('para_todas') && $request->categoria === null) {
            return redirect()
                ->back()
                ->withErrors(['erroCategoriaEdit'.$id => 'Escolha as categorias em que o campo será exibido.'])
                ->withInput($validated);
        }

        if ($campo->inscricoes_feitas_count > 0 && $campo->tipo !== $request->tipo_campo) {
            return redirect()
                ->back()
                ->withErrors(['tipo_campo' => 'Não é possível alterar o tipo de um campo que já possui respostas.'])
                ->withInput($validated);
        }

        $opcoes = $this->normalizarOpcoes($request->input('select_text', []));

        if ($request->tipo_campo === 'select' && count($opcoes) < 2) {
            return redirect()
                ->back()
                ->withErrors(['select_text' => 'Informe pelo menos duas opções para o campo de seleção.'])
                ->withInput($validated);
        }

        DB::transaction(function () use ($request, $evento, $campo, $opcoes) {
            $campo->titulo = $request->titulo_do_campo;

            if ($campo->inscricoes_feitas_count === 0) {
                $campo->tipo = $request->tipo_campo;
            }

            $campo->obrigatorio = $request->boolean('campo_obrigatorio');
            $campo->save();

            $this->sincronizarOpcoes($campo, $opcoes);
            $this->sincronizarCategorias($campo, $evento, $request);
        });

        return redirect()->back()->with(['success' => 'Campo atualizado com sucesso!']);
    }

    public function destroy($id)
    {
        $campo = CampoFormulario::findOrFail($id);

        $evento = $campo->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento);

        DB::transaction(function () use ($campo) {
            DB::table('valor_campo_extras')->where('campo_formulario_id', $campo->id)->delete();
            $campo->opcoes()->delete();
            $campo->categorias()->detach();
            $campo->delete();
        });

        return redirect()->back()->with(['success' => 'Campo extra deletado com sucesso!']);
    }

    private function rules(Request $request): array
    {
        return [
            'evento_id' => ['required', 'integer', 'exists:eventos,id'],
            'titulo_do_campo' => ['required', 'string', 'max:255'],
            'tipo_campo' => ['required', Rule::in(RegistrationFormFields::typeKeys())],
            'campo_obrigatorio' => ['nullable', 'boolean'],
            'para_todas' => ['nullable', 'boolean'],
            'categoria' => ['nullable', 'array'],
            'categoria.*' => ['integer', 'exists:categoria_participantes,id'],
            'select_text' => [$request->tipo_campo === 'select' ? 'required' : 'nullable', 'array'],
            'select_text.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function normalizarOpcoes(array $opcoes): array
    {
        return collect($opcoes)
            ->map(fn ($opcao) => trim((string) $opcao))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function sincronizarOpcoes(CampoFormulario $campo, array $opcoes): void
    {
        $campo->opcoes()->delete();

        if ($campo->tipo !== 'select') {
            return;
        }

        foreach ($opcoes as $opcao) {
            $campo->opcoes()->save(new CampoFormularioSelect(['nome' => $opcao]));
        }
    }

    private function sincronizarCategorias(CampoFormulario $campo, Evento $evento, Request $request): void
    {
        if ($request->boolean('para_todas')) {
            $categorias = $evento->categoriasParticipantes()->pluck('id');
        } else {
            $categorias = CategoriaParticipante::query()
                ->where('evento_id', $evento->id)
                ->whereIn('id', $request->input('categoria', []))
                ->pluck('id');
        }

        $campo->categorias()->sync($categorias);
    }
}
