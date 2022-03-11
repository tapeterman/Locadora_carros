<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;
    protected $table= 'locacoes';
    protected $fillable = [ 'cliente_id',
                            'carro_id',
                            'data_inicio_periodo',
                            'data_final_previsto_periodo',
                            'data_final_realizado_periodo',
                            'valor_diaria',
                            'km_inicial',
                            'km_final'
                        ];
    
    public function getRules()
    {
        
        return [
            'cliente_id'                    => 'exists:cliente,id',
            'carro_id'                      => 'exists:carro,id',
            'data_inicio_periodo'           => 'required|date',
            'data_final_previsto_periodo'   => 'required|date',
            'data_final_realizado_periodo'  => 'required|date',
            'valor_diaria'                  => 'required|numeric|min:0.01',
            'km_inicial'                    => 'required|integer',
            'km_final'                      => 'required|integer',
    
        ];
    }

    public function getFeedback()
    {
        return [
            'required'              => 'O campo :attribute é obrigatório!',
            'cliente_id.exists'     => 'É necessario um cliente valido!',
            'carro_id.exists'       => 'É necessario um carro valido!',
            'date'                  => 'Selecione uma data Valida!',
            'nome.min'              => 'O :attribute deve possuir pelo menos 3 caracteres!',
            'integer'               => 'O campo :attribute deve conter somente numeros!',
            'numeric'               => 'O campo :attribute deve ser 0.01 ou maior!' 
        ];

    }

    public function cliente(){

        return $this->belongsTo('App\Models\Cliente');
        
    }

    public function carro(){

        return $this->belongsTo('App\Models\Carro');
        
    }

}
