<?php

namespace App\Models;

class RecoveryCodesModel extends BaseModel {

    protected $table = 'recovery_codes';
    protected $primaryKey = 'id';

    protected $useSoftDeletes = false;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $useTimestamps = true;

    protected $beforeInsert = ['vinculaIdUsuario', 'geraCodigos'];

    protected $allowedFields = ['id', 'codigo', 'usuarios_id', 'usado'];

    // Gera códigos de recovery para este usuário
    protected function geraCodigos($data) {
        helper('text');
        $data['data']['codigo'] = strtoupper(bin2hex(random_bytes(8)));

        return $data;
    }

    // Apaga os recovery codes do usuário logado
    public function apagaRecoveryCodes() {
        return $this->where('usuarios_id', session()->id_usuario)->delete();
    }

    public function getByUsuariosId($id) {
        return $this->where('usuarios_id', $id)->findAll();
    }
}