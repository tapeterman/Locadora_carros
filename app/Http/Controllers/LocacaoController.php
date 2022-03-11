<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use Illuminate\Http\Request;
use App\Repositories\LocacaoRepository;

class LocacaoController extends Controller
{

    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }

    public function index(Request $request)
    {

        $locacaoRepository = new LocacaoRepository($this->locacao);
        //$atributos_modelo = 'modelo'. ($request->has('atributos_modelo') ? ':id,locacao_id,'.$request->atributos_modelo :  '');
        //$locacaoRepository->selectAtributosRegistrosRelacionados($atributos_modelo);

        if($request->has('filtro')){

            $locacaoRepository->filtro($request->filtro);

        }

        if($request->has('atributos')){

            $locacaoRepository->selectAtributos('id,'.$request->atributos);

        }

        return response()->json($locacaoRepository->getResultado(),200);
    }

    public function store(Request $request)
    {
        $request->validate($this->locacao->getRules(),$this->locacao->getFeedback());
        $locacao = $this->locacao->create([
                                            'cliente_id'                    => $request->cliente_id,
                                            'carro_id'                      => $request->carro_id,
                                            'data_inicio_periodo'           => $request->data_inicio_periodo,
                                            'data_final_previsto_periodo'   => $request->data_final_previsto_periodo,
                                            'data_final_realizado_periodo'  => $request->data_final_realizado_periodo,
                                            'valor_diaria'                  => $request->valor_diaria,
                                            'km_inicial'                    => $request->km_inicial,
                                            'km_final'                      => $request->km_final,

                                    ]);

        return response()->json($locacao,201);
    }

    public function show($id)
    {
        $locacao = $this->locacao->find($id);
        
        if($locacao === null){
            return response()->json(['erro' => 'O recurso não existe!'],404);
        }

        return response()->json($locacao,200);
    }

    public function update(Request $request, $id)
    {
        
        $locacao = $this->locacao->find($id);

        if($locacao === null){
            return response()->json(['erro' => 'Não foi possivel atualizar os dados o recurso não existe!'],404);
        }

        if($request->method() === 'PATCH'){
            $dynamicRules = array();

            foreach ($locacao->getRules() as $input => $rule) {

                if(array_key_exists($input,$request->all())){
                    $dynamicRules[$input] = $rule;
                }
            }

            $request->validate($dynamicRules,$this->locacao->getFeedback());

        } else{

            $request->validate($this->locacao->getRules(),$this->locacao->getFeedback());

        }

        $locacao->fill($request->all());
        $locacao->save();
        
        return response()->json($locacao,200);
    }

    public function destroy($id)
    {

        $locacao = $this->locacao->find($id);
        if($locacao === null){
            return response()->json(['erro' => 'Não foi possivel apagar o registro!'],404);
        }

        $locacao->delete();
        return response()->json(['msg' => 'Locacao removido com sucesso!'],200);
    }
}
