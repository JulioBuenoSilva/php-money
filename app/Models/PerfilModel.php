<?php

namespace App\Models;

class PerfilModel extends BaseModel
{
    protected $table = 'perfis';

    protected $primaryKey = 'chave';

    protected $useSoftDeletes = false;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $useTimestamps = true;


    protected $beforeInsert = ['geraChave', 'vinculaIdUsuario'];
    protected $beforeUpdate = ['checaPropriedade'];

    protected $allowedFields = [
        'descricao',
        'chave',
        'usuarios_id',
    ];

    protected $validationRules = [
        'descricao' => [
            'label' => 'Descrição',
            'rules' => 'required'
        ],
    ];

    /**
     * Gera uma array para ser  usada no from_dropdown
     *
     * @return void
     */
    public function formDropDown()
    {
        $this->select('id, descricao');

        $perfisArray = $this->orderBy('descricao')->findAll();

        $optionsPerfis = array_column($perfisArray, 'descricao', 'id');

        $optionSelecione = [
            '' => 'Selecione...'
        ];

        $selectConteudo = $optionSelecione + $optionsPerfis;
        return $selectConteudo;
    }
}
