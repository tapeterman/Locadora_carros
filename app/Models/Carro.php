<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;
    protected $fillable = [ 'modelo_id',
                            'placa',
                            'disponivel',
                            'km'
                        ];

    public function getRules()
    {
        
        return [
            'modelo_id'         => 'exists:modelos,id',
            'placa'             => 'required|unique:carros,placa,'.$this->id.'|min:8|max:8',
            'disponivel'        => 'required|boolean',
            'km'                => 'required|integer',
    
        ];
    }

    public function getFeedback()
    {
        return [
            'required'    => 'O campo :attribute é obrigatório!',
            'placa.unique' => 'O nome digitado já existe!',
            'placa.min'    => 'O :attribute deve possuir 7 caracteres!',
            'placa.max'    => 'O :attribute deve possuir 7 caracteres!',
            'integer'     => 'O campo :attribute deve conter somente numeros',
            'boolean'     => 'O campo :attribute deve ser obrigatoriamente sim ou não' 
        ];

    }

    public function modelo(){

        return $this->belongsTo('App\Models\Modelo');
        
    }
}
