<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    public function index(Request $request)
    {
        $marcas = array();
        
        if($request->has('atributos_modelos')){

            $atributos_modelos = 'id,marca_id,'.$request->atributos_modelos;
            $marcas = $this->marca->with('modelos:'.$atributos_modelos);

        } else {

            $marcas = $this->marca->with('modelos');

        }

        if($request->has('filtro')){
            $filter = explode(';',$request->filtro);
            foreach($filter as $key => $condition){
                $c = explode(':',$condition);
                $marcas = $marcas->where($c[0],$c[1],$c[2]);
            }
        }

        if($request->has('atributos')){

            $atributos = 'id,'.$request->atributos;
            $marcas = $marcas->selectRaw($atributos)->get();

        } else {
            $marcas = $marcas->get();
        }

        return response()->json($marcas,200);
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
        $marca = $this->marca->with('modelos')->find($id);
        
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

        //remove arquivo antigo se tiver atualização!
        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
        }
        
        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens','public');

        $marca->fill($request->all());
        $marca->imagem = $imagem_urn;
        $marca->save();
        
        return response()->json($marca,200);
    }

    public function destroy($id)
    {

        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro' => 'não foi possivel apagar o registro!'],404);
        }

        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return response()->json(['msg' => 'Marca removida com sucesso!'],200);
    }
}
