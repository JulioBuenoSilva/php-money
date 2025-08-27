<?php

namespace App\Models;

use CodeIgniter\Model;

class OrcamentoModel extends BaseModel
{
    protected $table            = 'orcamentos';
    protected $primaryKey       = 'chave';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'chave',
        'usuarios_id',
        'categorias_id',
        'descricao',
        'valor',
        'notificar_por_email',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $beforeInsert = ['corrigeValor', 'vinculaIdUsuario', 'geraChave'];
    protected $beforeUpdate = ['corrigeValor'];
    protected $beforeDelete = ['checaPropriedade'];

    protected $validationRules = [
        'descricao' => [
            'label' => 'Descrição',
            'rules' => 'required',
            'errors' => 'Campo {field} é obrigatório'
        ], 
        'categorias_id' => [
            'label' => 'Categoria',
            'rules' => 'required|numeric',
            'errors' => 'Campo {field} é obrigatório'
        ], 
        'valor' => [
            'label' => 'Categoria',
            'rules' => 'required',
            'errors' => 'Campo {field} é obrigatório'
        ]
        ];
        
    // retorna todos os orçamentos já com as categoras vinculadas a eles
    public function getAllWithCategorias() {
        $this->select(
            "
                orcamentos.chave as chave_orcamento,
                orcamentos.descricao as descricao_orcamento,
                categorias.chave as chave_categorias,
                categorias.descricao as descricao_categorias,
                valor,
                notificar_por_email
            "
        );
        $this->join('categorias', 'categorias.id = orcamentos.categorias_id');
        return $this->findAll();
    }
}
