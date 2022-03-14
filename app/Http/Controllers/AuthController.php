<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(request $request){

        $credencial = $request->all(['email','password']);
        $token =auth('api')->attempt($credencial);
        if($token){

            return response()->json(['token' =>$token],200);

        } else{
            return  response()->json(['erro' => 'Usuário ou senha Invalido!'],403);
        }
        return 'login';
    }
    public function logout(){
        auth('api')->logout();
        return response()->json(['msg' => 'logout realizado com sucesso!']);
    }
    public function refresh(){

        $token = auth('api')->refresh();
        return reponse()->json(['token' => $token]);

    }
    public function me(){
      
        return  response()->json(auth()->user());
    }
}
