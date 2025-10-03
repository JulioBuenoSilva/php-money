<?php namespace App\Models;

class PaginaModel extends BaseModel {
    protected $table = 'paginas';
    protected $primaryKey = 'chave';

    protected $useSoftDeletes = false;

    protected $createdField = 'created_at';
    protected $deletedField = 'deleted_at';
    protected $updatedField = 'updated_at';

    protected $useTimestamps = true;

    protected $skipValidation = false;

    protected $beforeInsert = ['geraChave'];

    protected $allowedFields = [
        'nome_amigavel',
        'nome_classe',
        'chave',
    ];
    
    protected $validationRules = [
        'nome_amigavel' => [
            'label' => 'Nome Amigável',
            'rules' => 'required',
            'errors' => [
                'required' => 'Campo {field} é obrigatório'
            ],
        ],
        'nome_classe' => [
            'label' => 'Nome Classe',
            'rules' => 'required|check_class_exists',
            'errors' => [
                'required' => 'Campo {field} é obrigatório',
                'check_class_exists' => 'Esta classe não existe',
            ],
        ]
    ];
}