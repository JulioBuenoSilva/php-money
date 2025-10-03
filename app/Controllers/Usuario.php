<?php

namespace App\Controllers;

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

class Usuario extends BaseController
{
    protected $usuarioModel;
    protected $idUsuario;
    protected $recoveryCodesModel;
    protected $chave;
    protected $perfilModel;
    protected $validation;
    protected $ga;
    protected $qrCodeUrl;

    public function __construct() {
        $this->usuarioModel = new \App\Models\UsuarioModel();
        $this->perfilModel = new \App\Models\PerfilModel();
        $this->validation = \Config\Services::validation();
        $this->idUsuario = session()->id_usuario;
        $this->chave = session()->chave;
        $this->ga = new GoogleAuthenticator();
        $this->recoveryCodesModel = new \App\Models\RecoveryCodesModel();
    }
    
    /**
     * Exibe a lista de usuários
     */
    public function index(): string
    {
        
        $data = [
            'meusDados' => $this->usuarioModel->getByIdUsuario($this->idUsuario),
            'meusUsuarios' => $this->usuarioModel->addOrder([
                'campo' => 'nome',
                'sentido' => 'ASC'
            ])->getByIdUsuarioPai($this->idUsuario)
        ];
        return view('usuarios/index', $data);
    }

    /**
     * Exibe o formulário para criação de um novo usuário
     */
    public function create()
    {
        $data = [
            'titulo' => 'Novo Usuário',
            'perfisDropDown' => $this->perfilModel->addUserId($this->idUsuario)->formDropDown()
        ];

        return view('usuarios/form', $data);
    }

    /**
     * Salva um usuário novo ou atualiza um usuário existente
     */
    public function store() {
        $post = $this->request->getPost();

        $validationRules = [
            'nome' => [
                'label' => 'Nome',
                'rules' => 'required',
                'errors' => [
                    'required' => 'O campo {field} é obrigatório'
                ]
            ],
            'email' => [
                'label' => 'E-mail',
                'rules' => "required|valid_email|is_unique[usuarios.email,usuarios.chave,{$post['chave']}]", //NÃO PODE TER ESPAÇOS ENTRE AS VÍRGULAS DO IS_UNIQUE,                
                'errors' => [
                    'required' => 'O campo {field} é obrigatório',
                    'valid_email' => 'Este e-mail: {value} parece ter um formato inválido',
                    'is_unique' => 'O e-mail {value} já está sendo utilizado.'
                ]
            ]
        ];

        $validationPerfil = [
            'perfis_id' => [
                'label'  => 'Perfil',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Campo {field} obrigatório para usuários filhos.',
                ],
            ],
        ];

        $validationSenhaAtual = [
            'senha_atual' => [
                'label' => 'Senha Atual',
                'rules' => 'required|check_senha_atual',
                'errors' => [
                    'required' => 'O campo {field} é obrigatório',
                    'check_senha_atual' => 'Senha atual inválida!'
                ]
            ]
        ];

        $validationRulesPassword = [
            'senha'         => [
                'label'  => 'Senha',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'O campo {field} é obrigatório'
                ]
            ],
            'senha_confirm' => [
                'label'  => 'Repita a Senha',
                'rules'  => 'required|matches[senha]',
                'errors' => [
                    'required' => 'O campo {field} é obrigatório'
                ]
            ],
        ];

        /**
         * Se o campo chave for vazio, indica um usuário novo. Então deve-se exigir a senha.
         */
        if (empty($post['chave'])) {
            $validationRules += $validationRulesPassword;
        }

        /**
         * Se os campos de senha forem vazios, então retiro do post o campo senha para não correr o risco
         * de uma atualização de um campo em branco
         */
        if (empty($post['senha']) && empty($post['senha_confirm'])) {
            unset($post['senha']);
        }

        /**
         * Se houver algo digitado no campo senha ou repita a senha, então, deve-se incluir a validação de senhas
         */
        if (!empty($post['senha']) || !empty($post['senha_confirm'])) {
            $validationRules += $validationRulesPassword;
            if ($post['chave'] == $this->session->chave) {
                $validationRules += $validationSenhaAtual;
            }
        }

        /**
         * Se for um novo cadastro e existir alguém logado, indica que um usuário filho está sendo cadastrdo.
         * Então, deve-se informar o perfil
         */
        if (session()->has('id_usuario') && empty($post['chave'])) {
            $validationRules += $validationPerfil;
        }

        // dando dump and die na chave da session atual
        $this->validation->setRules($validationRules);

        if ($this->validation->withRequest($this->request)->run()) {
            /* 
             * Se não existir a chave, trata-se da criação de um usuário filho, portanto, deve-se preencher o usuarios_pai, o campo email_confirmado vem como true para o usuário filho
             * Se existir, trata-se de uma atualização de um usuário existente
             * Não é permitido alterar o próprio email
             * Se for o usuário_pai é permitido alterar o email apenas dos usuários cadastrados por ele e pelos usuários descendentes dele
             */ 

            if (empty($post['chave'])) {
                if (!session()->has('id_usuario')) {
                    return redirect()->to('/mensagem/erro')->with('mensagem', 'ERRO - Não foi possível cadastrar o usuário. Por favor, tente novamente.');
                }
                $post['usuarios_pai'] = $this->idUsuario;
                $post['email_confirmado'] = true;
                
            } else {
                // Não permito a edição do próprio email
                if ($post['chave'] == $this->chave) {
                    unset($post['email']);
                    unset($post['perfis_id']);
                } else {
                    /* 
                     * Veriicando se o usuário que está sendo editado descende do usuário logado
                     * Para isso, o valor usuarios_id deve estar na array ids_filhos
                     */
                    $idProprietário = $this->usuarioModel->getByChave($post['chave'])['id_usuario'];
                    if (!in_array($idProprietário, $this->session->ids_filhos)) {
                        return redirect()->to('/mensagem/erro')->with('mensagem', 'ERRO - Não foi possível atualizar o usuário. Por favor, tente novamente.');
                    }
                }
            }
            
            if ($this->usuarioModel->save($post)) {   
                if (empty($post['chave'])) {
                    $idUsuario = $this->usuarioModel->getInsertID();
                    $chaveUsuario = $this->usuarioModel->getById($idUsuario)['chave'];
                    return redirect()->to("/usuario/{$chaveUsuario}/edit")->with('mensagem', "Usuário cadastrado com sucesso!");
                } else {
                    return redirect()->to("/usuario")->with('mensagem', "Usuário atualizado com sucesso!");
                }
            } else {
                return redirect()->to('/mensagem/erro')->with('mensagem', 'ERRO - Não foi possível cadastrar o usuário. Por favor, tente novamente.');
            }
        } else {
            echo view('usuarios/form', [
                'titulo' => !empty($post['chave']) ? 'Editar usuário' : 'Novo usuário',
                'errors' => $this->validation->getErrors(),
                'perfisDropDown' => $this->perfilModel->addUserId($this->session->id_usuario)->formDropDown(),
                'nomePerfil' => $this->perfilModel->getById($post['perfis_id'])['descricao'] ?? null,
                'chave' => $post['chave']
            ]);
        }
    }

    public function edit($chave) {
        $dadosUsuario = $this->usuarioModel->getByChave($chave);

        if (is_null($dadosUsuario) || !in_array($dadosUsuario['id'], $this->session->ids_filhos)) {
            return redirect()->to('/mensagem/erro')->with('mensagem', 'ERRO - Usuário não encontrado. Por favor, tente novamente.');
        }

        echo view('usuarios/form', [
            'titulo' => 'Editar usuário',
            'usuariosFilhos' => $this->usuarioModel->getUsuariosFilhos($dadosUsuario['id']),
            'perfisDropDown' => $this->perfilModel->addUserId($this->idUsuario)->formDropDown(),
            'nomePerfil' => $this->perfilModel->getById($dadosUsuario['perfis_id'])['descricao'] ?? null,
            'usuario' => $dadosUsuario,
            'chave' => $chave,
            'recoveryCodes' => $this->recoveryCodesModel->getByUsuariosId($this->idUsuario)
        ]);
    }

    public function delete($chave) {
        if ($this->usuarioModel->delete($chave)) {
            return redirect()->to("/usuario")->with('mensagem', "Usuário excluído com sucesso!");
        } else {
            return redirect()->to('/mensagem/erro')->with('mensagem', 'ERRO - Não foi possível excluir o usuário. Por favor, tente novamente.');
        }
    }

    /**
     * Carrega a página para ativação do Google Authenticator
     */
    public function googleAuth() {
        if (!session()->has('secret_google_auth')) {
            $secret = $this->ga->generateSecret();
            session()->set('secret_google_auth', $secret);
        } else {
            $secret = session()->get('secret_google_auth');
        }

        $qrCodeUrl = GoogleQrUrl::generate(session()->email . '@PHP-Money', $secret);

        $dados = [
            'titulo' => 'Ativação do Google Authenticator',
            'secret' => $secret,
            'qrCodeUrl' => $qrCodeUrl,
            'chave' => $this->chave,
        ];

        echo view('usuarios/qr_code_google_auth', $dados);
    }


    /**
     * Armazena o código do Google Authenticator no registro do usuário
     */
    public function storeGoogleAuth() {
        $post = $this->request->getPost();
        $secret = $post['secret'];

        $dadosUsuario = $this->usuarioModel->getByChave($post['chave']);
        if (is_null($dadosUsuario)) {
            return redirect()->to('/mensagem/erro')->with('mensagem', 'ERRO - Usuário não encontrado. Por favor, tente novamente.');
        }
        if ($this->ga->checkCode($secret, $post['code'])) {
            $dadosGoogle = [
                'chave' => $post['chave'],
                'secret_google_auth' => $secret
            ];
            if ($this->usuarioModel->save($dadosGoogle)) {
                return redirect()->to("/usuario")->with('mensagem', [
                    'mensagem' => "Google Authenticator ativado com sucesso! No próximo login utilize o código gerado no aplicativo.",
                    'link' => [
                        'texto' => 'Voltar para Edição de Usuário',
                        'to' => "usuario/{$post['chave']}/edit",
                    ],
                ]);
            } else {
                return redirect()->to('/mensagem/erro')->with('mensagem', 'ERRO - Não foi possível ativar o Google Authenticator. Por favor, tente novamente.');
            }
        } else {
            return redirect()->to('/usuario/googleAuth')->with('mensagem', 'ERRO - Código inválido. Por favor, tente novamente.');
        }
    }

    public function desativaAuth2Fatores() {
        $chave = session()->chave;
        $request = $this->usuarioModel->save([
            'chave' => $chave,
            'secret_google_auth' => null
        ]);
        // Apago os recovery codes deste usuário
        // $this->recoveryCodesModel->apagaRecoveryCodes();
        [$usuario] = explode('@', session()->email);
        setCookie(md5($usuario), '', time() - 3600);
        return redirect()->to("/usuario/{$chave}/edit")->with('mensagem', 'Autenticação de dois fatores desativada com sucesso!');
    }

    // Gera os códigos de recuperação
    public function createBackupCodes() {
        // primeiro apago os códigos atuais
        if ($this->recoveryCodesModel->apagaRecoveryCodes()) {
            for ($i = 1;  $i <= 16;  $i++) {
                $this->recoveryCodesModel->save(['usado' => false]);
            }
            return redirect()->to("usuario/{$this->chave}/edit")->with('mensagem',
            'Codigos de recuperação gerados. Guarde-os em um lugar seguro');
        }
    }

    // Retorna a imagem de um usuário
    public function getFoto($chave = null) {
        $dadosUsuario = $this->usuarioModel->getByChave($chave);

        if (!is_null($dadosUsuario)) {
            $foto = $dadosUsuario['foto'];
            if (!is_null($foto)) {
                $filename = WRITEPATH . 'uploads/' . $foto;
                if (file_exists($filename)) {
                    $imgInfo = getImageSize($filename);
                    $this->response->setHeader('Content-Type', $imgInfo['mime']);
                    echo file_get_contents($filename);
                } else {        
                    echo json_encode(['error' => true, 'message' => 'Arquivo não encontrado']);
                    die();
                }
            } else {
                echo json_encode(['error' => true, 'message' => 'Usuário sem foto cadastrada']);
            }
        } else {
            echo json_encode([
                'error' => true,
                'message' => 'Usuário não encontrado'
            ]);
        }
    }
}
