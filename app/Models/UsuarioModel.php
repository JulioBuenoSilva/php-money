<?php 
namespace App\Models;

class UsuarioModel extends BaseModel
{
    protected $table = 'usuarios';

    protected $primaryKey = 'chave';

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $useTimestamps = true;

    protected $useSoftDeletes = true;

    protected $beforeInsert = ['geraToken', 'geraChave', 'hashPassword'];
    protected $beforeUpdate = ['hashPassword', 'checaPropriedade'];
    protected $afterInsert = ['updateIdsFilhos'];
    protected $afterUpdate = ['updateIdsFilhos'];
    protected $allowedFields = [
        'chave',
        'nome',
        'perfis_id',
        'usuario_pai',
        'email',
        'email_confirmado',
        'foto',
        'senha',
        'token_confirmacao_email',
        'token_criado_em',
        'ativo',
        'admin',
        'secret_google_auth',
    ];

    protected function geraToken(array $data) {
        if (!isset($data['data']['token_confirmacao_email'])) {
            $data['data']['token_confirmacao_email'] = md5(uniqid(rand(), true));
            $data['data']['token_criado_em'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * Encripta a senha antes de salvar no banco de dados
     * 
     * @param array $data
     * @return array
     */
    protected function hashPassword(array $data) {
        if (isset($data['data']['senha'])) {
            $data['data']['senha'] = password_hash($data['data']['senha'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Retorna os dados do usuário logado pelo ID
     * @param int $idUsuario
     * @return object|null
     */
    public function getByIdUsuario($idUsuario) {
        $this->builder()->select("
            usuarios.chave,
            usuarios.nome,
            usuarios.email,
            usuarios.created_at,
            usuarios.token_confirmacao_email,
            usuarios.ativo,
            usuarios.id as id_usuario,
            usuarios.chave as chave_usuario,
            perfis.id as perfis_id,
            perfis.usuarios_id,
            perfis.chave as chave_perfil,
            perfis.descricao as descricao_perfil
        ")
        ->join('perfis', 'perfis.usuarios_id = usuarios.id', 'LEFT');
        return $this->where('usuarios.id', $idUsuario)->first();
    }

    public function getByIdUsuarioPai($id) {
        $this->builder()->select("
            usuarios.chave,
            usuarios.nome,
            usuarios.email,
            usuarios.created_at,
            usuarios.token_confirmacao_email,
            usuarios.ativo,
            usuarios.id as id_usuario,
            usuarios.chave as chave_usuario,
            perfis.id as perfis_id,
            perfis.usuarios_id,
            perfis.chave as chave_perfil,
            perfis.descricao as descricao_perfil
        ")
        ->join('perfis', 'perfis.usuarios_id = usuarios.id', 'LEFT');
        return $this->where('usuarios.usuario_pai', $id)->findAll();
    }

    /**
     * Recupera o ID do usuário pai mais alto na hierarquia
     */
    public function getIdPai(int $id) {
        while (true) {
            $dados = $this
                ->select('id, usuario_pai')
                ->where('id', $id)
                ->first();

            if (is_null($dados['usuario_pai'])) {
                return $id; // chegou no último pai
            }

            $id = $dados['usuario_pai']; // sobe na hierarquia
        }
    }
    
    /**
     * Retorna todos os descendentes de um usuário
     * - Nós internos: array associativo (nome => filhos)
     * - Folhas: array numérico de nomes
     */
    public function getUsuariosFilhos(int $id, bool $fromAdmin = false) {

        $filhos = $this
            ->select('id, chave, nome, usuario_pai')
            ->where('usuario_pai', $id)
            ->findAll();

        if (empty($filhos)) {
            return [];
        }

        $arvore = [];

        foreach ($filhos as $filho) {
            $urlBase = $fromAdmin ? 'admin/usuario/' : 'usuario/';
            $link = anchor(
                $urlBase . $filho['chave'] . '/edit',
                esc($filho['nome'])
            );

            $descendentes = $this->getUsuariosFilhos($filho['id'], $fromAdmin);

            if (empty($descendentes)) {
                $arvore[] = $link;
            } else {
                $arvore[$link] = $descendentes;
            }
        }

        return $arvore;
    }



}
?>