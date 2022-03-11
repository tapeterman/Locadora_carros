<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Repositories\ClienteRepository;

class ClienteController extends Controller
{

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    public function index(Request $request)
    {

        $clienteRepository = new ClienteRepository($this->cliente);

        if($request->has('filtro')){

            $clienteRepository->filtro($request->filtro);

        }

        if($request->has('atributos')){

            $clienteRepository->selectAtributos('id,'.$request->atributos);

        }

        return response()->json($clienteRepository->getResultado(),200);
    }

    public function store(Request $request)
    {
        $request->validate($this->cliente->getRules(),$this->cliente->getFeedback());
        $cliente = $this->cliente->create([
                                        'nome'     => $request->nome,
                                    ]);

        return response()->json($cliente,201);
    }

    public function show($id)
    {
        $cliente = $this->cliente->find($id);
        
        if($cliente === null){
            return response()->json(['erro' => 'O recurso não existe!'],404);
        }

        return response()->json($cliente,200);
    }

    public function update(Request $request, $id)
    {
        
        $cliente = $this->cliente->find($id);

        if($cliente === null){
            return response()->json(['erro' => 'Não foi possivel atualizar os dados o recurso não existe!'],404);
        }

        if($request->method() === 'PATCH'){
            $dynamicRules = array();

            foreach ($cliente->getRules() as $input => $rule) {

                if(array_key_exists($input,$request->all())){
                    $dynamicRules[$input] = $rule;
                }
            }

            $request->validate($dynamicRules,$this->cliente->getFeedback());

        } else{

            $request->validate($this->cliente->getRules(),$this->cliente->getFeedback());

        }

        $cliente->fill($request->all());
        $cliente->save();
        
        return response()->json($cliente,200);
    }

    public function destroy($id)
    {

        $cliente = $this->cliente->find($id);
        if($cliente === null){
            return response()->json(['erro' => 'Não foi possivel apagar o registro!'],404);
        }

        $cliente->delete();
        return response()->json(['msg' => 'Cliente removido com sucesso!'],200);
    }
}
