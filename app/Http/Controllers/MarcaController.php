<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    public function index()
    {
        $marcas = $this->marca->all();
        return $marcas;
    }

    public function store(Request $request)
    {
        $request->validate($this->marca->getRules(),$this->marca->getFeedback());
        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens','public');
        $marca = $this->marca->create([
                                        'nome' => $request->nome,
                                        'imagem' => $imagem_urn,

                                    ]);

        return response()->json($marca,201);
    }

    public function show($id)
    {
        $marca = $this->marca->find($id);
        
        if($marca === null){
            return response()->json(['erro' => 'O recurso não existe!'],404);
        }

        return response()->json($marca,200);
    }

    public function update(Request $request, $id)
    {
        
        $marca = $this->marca->find($id);

        if($marca === null){
            return response()->json(['erro' => 'Não foi possivel atualizar os dados o recurso não existe!'],404);
        }

        if($request->method() === 'PATCH'){
            $dynamicRules = array();

            foreach ($marca->getRules() as $input => $rule) {

                if(array_key_exists($input,$request->all())){
                    $dynamicRules[$input] = $rule;
                }
            }

            $request->validate($dynamicRules,$this->marca->getFeedback());

        } else{

            $request->validate($this->marca->getRules(),$this->marca->getFeedback());

        }
        

        $marca->update($request->all());
        return response()->json($marca,200);
    }

    public function destroy($id)
    {

        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro' => 'não foi possivel apagar o registro!'],404);
        }
        $marca->delete();
        return response()->json(['msg' => 'Marca removida com sucesso!'],200);
    }
}
