<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $fillable = ['nome'];

    
    public function getRules()
    {
        
        return [
            'nome'   => 'required|unique:clientes,nome,'.$this->id.'|min:3',
        ];
    }

    public function getFeedback()
    {
        return [
            'required'    => 'o campo :attribute é obrigatório!',
            'nome.unique' => 'O nome digitado já existe!',
            'nome.min'    => 'o :attribute deve possuir pelo menos 3 caracteres!'
        ];

    }

}
