<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use Illuminate\Http\Request;
use App\Repositories\CarroRepository;

class CarroController extends Controller
{
    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }

    public function index(Request $request)
    {

        $carroRepository = new CarroRepository($this->carro);
        $atributos_modelo = 'modelo'. ($request->has('atributos_modelo') ? ':id,carro_id,'.$request->atributos_modelo :  '');
        $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);

        if($request->has('filtro')){

            $carroRepository->filtro($request->filtro);

        }

        if($request->has('atributos')){

            $carroRepository->selectAtributos('id,'.$request->atributos);

        }

        return response()->json($carroRepository->getResultado(),200);
    }

    public function store(Request $request)
    {
        $request->validate($this->carro->getRules(),$this->carro->getFeedback());
        $carro = $this->carro->create([
                                        'modelo_id'     => $request->modelo_id,
                                        'placa'         => $request->placa,
                                        'disponivel'    => $request->disponivel,
                                        'km'            => $request->km,

                                    ]);

        return response()->json($carro,201);
    }

    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        
        if($carro === null){
            return response()->json(['erro' => 'O recurso não existe!'],404);
        }

        return response()->json($carro,200);
    }

    public function update(Request $request, $id)
    {
        
        $carro = $this->carro->find($id);

        if($carro === null){
            return response()->json(['erro' => 'Não foi possivel atualizar os dados o recurso não existe!'],404);
        }

        if($request->method() === 'PATCH'){
            $dynamicRules = array();

            foreach ($carro->getRules() as $input => $rule) {

                if(array_key_exists($input,$request->all())){
                    $dynamicRules[$input] = $rule;
                }
            }

            $request->validate($dynamicRules,$this->carro->getFeedback());

        } else{

            $request->validate($this->carro->getRules(),$this->carro->getFeedback());

        }

        $carro->fill($request->all());
        $carro->save();
        
        return response()->json($carro,200);
    }

    public function destroy($id)
    {

        $carro = $this->carro->find($id);
        if($carro === null){
            return response()->json(['erro' => 'Não foi possivel apagar o registro!'],404);
        }

        $carro->delete();
        return response()->json(['msg' => 'Carro removido com sucesso!'],200);
    }
}
