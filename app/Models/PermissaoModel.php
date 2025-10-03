<?php

namespace App\Models;

use App\Controllers\Perfil;

class PermissaoModel extends BaseModel
{
    protected $table = 'permissoes';

    protected $primaryKey = 'id';

    protected $useSoftDeletes = false;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $useTimestamps = true;

    protected $beforeInsert = ['geraChave'];

    protected $allowedFields = [
        'regras',
        'chave',
        'paginas_id',
        'perfis_id'
    ];

    /**
     * Retorna todas as permissões de uma página vinculada a um perfil
     *
     * @param [type] $paginas_id
     * @param [type] $perfis_id
     * @return array
     */
    public function getByIdPaginaAndIdPerfil($paginas_id = null, $perfis_id = null)
    {
        return $this->where([
            'perfis_id' => $perfis_id,
            'paginas_id' => $paginas_id
        ])->findAll();
    }

    /**
     * Retorna todas as páginas e as permissões do perfil informado.
     *
     * @param [type] $perfis_id
     * @return void
     */
    public function getByPerfisId($perfis_id)
    {
        return $this->where('perfis_id', $perfis_id)->findAll();
    }
}
