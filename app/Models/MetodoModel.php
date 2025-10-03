<?php

namespace App\Models;

class MetodoModel extends BaseModel
{
    protected $table = 'metodos';

    protected $primaryKey = 'chave';

    protected $useSoftDeletes = false;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $useTimestamps = true;

    protected $skipValidation = false;

    protected $beforeInsert = ['geraChave'];

    protected $allowedFields = [
        'nome_amigavel',
        'nome_metodo',
        'paginas_id',
    ];

    protected $validationRules = [
        'nome_amigavel' => [
            'label' => 'Nome Amigável',
            'rules' => 'required',
            'errors' => [
                'required' => 'Campo {field} obrigatório',
            ],
        ],
    ];

    /**
     * Retorna o nome amigável de um método filtrado pelo paginas_id e nome_metodo
     *
     * @param [type] $pagina_id
     * @param [type] $nome_metodo
     * @return void
     */
    public function getNomeAmigavel($pagina_id = null, $nome_metodo = null)
    {
        $row = $this->select('nome_amigavel')
            ->where([
                'paginas_id'  => $pagina_id,
                'nome_metodo' => $nome_metodo
            ])
            ->first();

        return $row['nome_amigavel'] ?? null;
    }


    /**
     * Apaga os registros através do campo paginas_id
     *
     * @param [type] $id_pagina
     * @return void
     */
    public function deleteByPaginasId($id_pagina = null)
    {
        return $this->where('paginas_id', $id_pagina)->delete();
    }

    /**
     * Retorna todos os método de uma página.
     *
     * @param [type] $id_pagina
     * @return void
     */
    public function getByPaginasId($id_pagina)
    {
        return $this->where('paginas_id', $id_pagina)->findAll();
    }
}
