<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;
    protected $fillable = ['marca_id','nome','imagem','numero_portas','lugares','air_bag','abs'];

    public function getRules()
    {
        
        return [
            'marca_id'          => 'exists:marcas,id',
            'nome'              => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem'            => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas'     => 'required|integer|digits_between:1,5',
            'lugares'           => 'required|integer|digits_between:1,20',
            'air_bag'           => 'required|boolean',
            'abs'               => 'required|boolean'
        ];
    }

    public function getFeedback()
    {
        return [
            'required'    => 'O campo :attribute é obrigatório!',
            'image.mimes' => 'O arquivo deve ter a extensão png,jpeg ou jpg',
            'nome.unique' => 'O nome digitado já existe!',
            'nome.min'    => 'O :attribute deve possuir pelo menos 3 caracteres!',
            'integer'     => 'O campo :attribute deve conter somente numeros',
            'boolean'     => 'O campo :attribute deve ser obrigatoriamente sim ou não' 
        ];

    }
}
