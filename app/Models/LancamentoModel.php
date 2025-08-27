<?php

namespace App\Models;


class Lancamento extends BaseModel
{
    protected $table = 'lancamentos';

    protected $primaryKey = 'chave';

    protected $useSoftDeletes = true; 
    
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $beforeInsert = ['vinculaIdUsuario', 'geraChave', 'corrigeValor', 'converteData'];
    protected $beforeUpdate = ['converteData', 'corrigeValor', 'checaPropriedade'];

    protected $useTimestamps = true;


    protected $allowedFields = [
        'usuarios_id',
        'chave',
        'categorias_id',
        'descricao',
        'valor',
        'data',
        'notificar_por_email',
        'consolidado',
        'categoria_id',
    ];

    protected $validationRules = [
        'descricao' => [
            'label' => 'Descrição',
            'rules' => 'required'
        ],
        'categorias_id' => [
            'label' => "Categoria",
            'rules' => 'required'
        ],
        'valor' => [
            'label' => "Valor",
            'rules' => 'required'
        ],
        'data' => [
            'label' => "Data",
            'rules' => 'required'
        ],
    ];
}