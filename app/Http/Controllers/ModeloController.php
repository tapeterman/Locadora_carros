<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\ModeloRepository;

class ModeloController extends Controller
{

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }
    public function index(Request $request)
    {

        $marcaRepository = new ModeloRepository($this->marca);
        $atributos_marca = 'marca'. ($request->has('atributos_modelos') ? ':id,'.$request->atributos_marca :  '');
        $marcaRepository->selectAtributosRegistrosRelacionados($atributos_marca);

        if($request->has('filtro')){

            $marcaRepository->filtro($request->filtro);

        }

        if($request->has('atributos')){

            $marcaRepository->selectAtributos('marca_id,'.$request->atributos);

        }

        return response()->json($marcaRepository->getResultado(),200);
    }

    public function store(Request $request)
    {
        $request->validate($this->modelo->getRules(),$this->modelo->getFeedback());

        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens/modelos','public');
        $modelo = $this->modelo->create([
                                        'marca_id'      => $request->marca_id,
                                        'nome'          => $request->nome,
                                        'imagem'        => $imagem_urn,
                                        'numero_portas' => $request->numero_portas,
                                        'lugares'       => $request->lugares,
                                        'air_bag'       => $request->air_bag,
                                        'abs'           => $request->abs,
                                    ]);

        return response()->json($modelo,201);
    }

    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        
        if($modelo === null){
            return response()->json(['erro' => 'O recurso não existe!'],404);
        }

        return response()->json($modelo,200);
    }

    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);

        if($modelo === null){
            return response()->json(['erro' => 'Não foi possivel atualizar os dados o recurso não existe!'],404);
        }

        if($request->method() === 'PATCH'){
            $dynamicRules = array();

            foreach ($modelo->getRules() as $input => $rule) {

                if(array_key_exists($input,$request->all())){
                    $dynamicRules[$input] = $rule;
                }
            }

            $request->validate($dynamicRules,$this->modelo->getFeedback());

        } else{

            $request->validate($this->modelo->getRules(),$this->modelo->getFeedback());

        }

        //remove arquivo antigo se tiver atualização!
        if($request->file('imagem')){
            Storage::disk('public')->delete($modelo->imagem);
        }
        
        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens/modelos','public');

        $modelo->fill($request->all());
        $modelo->imagem = $imagem_urn;
        $modelo->save();

        return response()->json($modelo,200);

    }

    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === null){
            return response()->json(['erro' => 'não foi possivel apagar o registro!'],404);
        }

        Storage::disk('public')->delete($modelo->imagem);
        
        $modelo->delete();
        return response()->json(['msg' => 'O Modelo removido com sucesso!'],200);
    }
}
