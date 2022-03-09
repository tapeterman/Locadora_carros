<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome','imagem'];

    public function getRules()
    {
        
        return [
            'nome'   => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png',
        ];
    }

    public function getFeedback()
    {
        return [
            'required'    => 'o campo :attribute é obrigatório!',
            'image.mimes' => 'O arquivo deve ter a extensão PNG',
            'nome.unique' => 'O nome digitado já existe!',
            'nome.min'    => 'o :attribute deve possuir pelo menos 3 caracteres!'
        ];

    }

    public function modelos(){

        return $this->hasMany('App\Models\Modelo');
        
    }
}
