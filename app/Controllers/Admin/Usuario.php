<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\PerfilModel;
use App\Models\RecoveryCodesModel;
use App\Models\UsuarioModel;

class Usuario extends BaseController
{
    protected $id_usuario;
    protected $usuarioModel;
    protected $recoveryCodesModel;
    protected $perfilModel;
    protected $validation;


    public function __construct()
    {
        $this->usuarioModel =  new UsuarioModel();
        $this->perfilModel  = new PerfilModel();
        $this->recoveryCodesModel = new RecoveryCodesModel();
        $this->validation   = \Config\Services::validation();
    }

    /**
     * Chama a view de usuários.
     *
     * @return void
     */
    public function index()
    {

        $dados = [
            'totalUsuarios' => $this->usuarioModel->countAll(),
            'usuarios' => $this->usuarioModel->addOrder([
                'campo' => 'usuarios.created_at',
                'sentido' => 'desc',
            ])->paginate(5),
            'pager' => $this->usuarioModel->pager
        ];
        echo view('admin/usuarios/index', $dados);
    }

    public function edit($chave)
    {

        $dadosUsuario = $this->usuarioModel->getByChave($chave);
        if (!is_null($dadosUsuario)) {
            echo view('admin/usuarios/form', [
                'titulo' => 'Editar Usuário',
                'usuariosFilhos' => $this->usuarioModel->getUsuariosFilhos($dadosUsuario['id'], true),
                'perfisDropDown' => $this->perfilModel->addUserId($this->session->id_usuario)->formDropDown(),
                'usuario' => $dadosUsuario,
                'nomePerfil' => $this->perfilModel->getById($dadosUsuario['perfis_id'])['descricao'],
                'chave' => $chave
            ]);
        } else {
            return redirect()->to('/mensagem/erro')->with('mensagem', [
                'mensagem' => 'ERRO - Usuário não encontrado',
                'link' => [
                    'to' => 'admin/usuario',
                    'texto' => 'Tela de Usuários'
                ]
            ]);
        }
    }

    public function store()
    {
        $post = $this->request->getPost();
        $validationRules = [
            'nome'  => [
                'label'  => 'nome',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Campo {field} obrigatório',
                ],
            ],
            'email' => [
                'label'  => "E-mail",
                'rules'  => "required|valid_email|is_unique[usuarios.email,usuarios.chave,{$post['chave']}]", //NÃO PODE TER ESPAÇOS AQUI: usuarios.email,usuarios.chave,{$post['chave']}
                'errors' => [
                    'required'    => 'Campo {field} obrigatório',
                    'valid_email' => 'Este e-mail: {value} parece ter um formato inválido',
                    'is_unique'   => 'O e-mail {value} já está sendo utilizado',
                ],
            ],
        ];


        $validationRulesPassword = [
            'senha'         => [
                'label'  => 'Senha',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Campo {field} obrigatório',
                ],
            ],
            'senha_confirm' => [
                'label'  => 'Repita a Senha',
                'rules'  => 'required|matches[senha]',
                'errors' => [
                    'required' => 'Campo {field} obrigatório',
                ],
            ],
        ];

        //Só valida os campos senha, se houver algum valor digitado em um dos campos.
        if (!empty($post['senha']) || !empty($post['senha_confirm'])) {
            $validationRules += $validationRulesPassword;
        }

        /**
         * Se os campos de senha forem vazios, então retiro do post o campo senha para não correr o risco
         * de uma atualização de um campo em branco
         */
        if (empty($post['senha']) && empty($post['senha_confirm'])) {
            unset($post['senha']);
        }

        $this->validation->setRules($validationRules);

        if ($this->validation->withRequest($this->request)->run()) {
            if ($this->usuarioModel->save($post)) {
                return redirect()->to("/admin/usuario")->with('mensagem', 'Registro salvo com sucesso.');
            }
        } else {
            echo view('admin/usuarios/form', [
                'titulo'         => !empty($post['chave']) ? 'Editar Usuário' : 'Novo Usuário',
                'errors'         => $this->validation->getErrors(),
                'perfisDropDown' => $this->perfilModel->addUserId($this->session->id_usuario)->formDropDown(),
                'nomePerfil'     => $this->perfilModel->get($post['perfis_id'])['descricao'],
                'chave'          => $post['chave']
            ]);
        }
    }

    /**
     * Apaga um registro do banco.
     *
     * @param [type] $chave
     * @return void
     */
    public function delete($chave = null)
    {
        if ($this->usuarioModel->delete($chave)) {
            return redirect()->to('/admin/usuario')->with('mensagem', 'Registro excluído com sucesso.'); //Chama o controller usuários
        }
    }
}
