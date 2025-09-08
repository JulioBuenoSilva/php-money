<?php 

namespace App\Models;

use PhpParser\Node\Expr\Cast\Object_;

class CategoriaModel extends BaseModel
{
    protected $table = 'categorias';
    protected $primaryKey = 'chave';
    protected $useSoftDeletes = true;
    
    protected $deletedField = 'deleted_at';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $beforeDelete = ['checaPropriedade'];

    protected $useTimestamps = true;

    protected $allowedFields = [
        'usuarios_id', 
        'chave',
        'tipo',
        'descricao'
    ];

    protected $beforeInsert = [
        'vinculaIdUsuario',
        'geraChave' 
    ];

    protected $validationRules = [
        'descricao' => [
            'label' => 'Descrição',
            'rules' => 'required',
            'errors' => 'Campo {field} é obrigatório'
        ],
        'tipo' => [
            'label' => 'Tipo',
            'rules' => 'required',
            'errors' => 'Campo {field} é obrigatório'
        ]
    ];

    // gera uma array de categorias pronta para ser populada na função form_dropdown
    // se for passado o parametro $opcaoNova insere a opção "Nova Categoria"
    public function formDropDown(array $params) {
        $this->select('id, descricao, tipo');
        
        if (!is_null($params) && isset($params['tipo'])) {
            $this->where(['tipo' => $params['tipo']]);
        }

        if (!is_null($params) && isset($params['id'])) {
            $this->where(['id' => $params['id']]);
        }

        $categoriasArray = $this->findAll();
        $optionsCategorias = array_column($categoriasArray, 'descricao', 'id');

        $optionsSelecione = [
            '' => 'Selecione...'
        ];

        $selectConteudo = $optionsSelecione + $optionsCategorias;
        $novaCategoria = [];
        if (isset($params['opcaoNova'])) {
            if ((bool)$params['opcaoNova'] === true) {
                $novaCategoria = [
                    '---' => [
                        'n' => 'Nova categoria...'
                    ]
                ];
            }
        }
        
        return $selectConteudo + $novaCategoria;
    }

    // retorna as categorias que possuem lançamentos vinculados
    public function getComLancamentos() {
        $this->select(' 
            categorias.usuarios_id, 
            categorias.descricao AS descricao_categoria, 
            categorias.id AS id_categoria, 
            lancamentos.descricao AS descricao_lancamento, 
            lancamentos.id AS id_lancamentos'
        );
        $this->join('lancamentos', 'lancamentos.categorias_id = categorias.id');
        $this->groupBy('descricao_categoria');
        return $this->findAll();
    }
}