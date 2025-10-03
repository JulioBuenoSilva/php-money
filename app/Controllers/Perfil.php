<?php

namespace App\Controllers;

class Perfil extends BaseController
{
    protected $perfilModel;
    protected $paginaModel;
    protected $permissaoModel;
    protected $metodoModel;

    public function __construct() {
        $this->perfilModel = new \App\Models\PerfilModel();
        $this->paginaModel = new \App\Models\PaginaModel();
        $this->permissaoModel = new \App\Models\PermissaoModel();
        $this->metodoModel = new \App\Models\MetodoModel();
    }

    public function index(): string
    {
        $data = [
            'perfis' => $this->perfilModel->addUserId($this->session->id_usuario)->addOrder([
                'campo' => 'perfis.descricao',
                'sentido' => 'asc'
            ])->findAll()
        ];

        return view('perfis/index', $data);
    }

    // Chama a view de cadastro
    public function create() {
        $paginas = $this->paginaModel->findAll();
        $result = [];
        foreach ($paginas as $pagina) {
            $result[] = [
                'paginas_id' => $pagina['id'],
                'nome_amigavel' => $pagina['nome_amigavel'],
                'nome_classe' => $pagina['nome_classe'],
                'metodos' => $this->metodoModel->getByPaginasId($pagina['id']),
                'regras' => '',
                'id_permissao' => '',
            ];
        }

        echo view('perfis/form', [
            'titulo' => 'Novo Perfil',
            'paginas' => $result,
        ]);
    }

    public function edit($chave) {
        $perfil = $this->perfilModel->getByChave($chave);
        $result = [];

        if (!is_null($perfil)) {
            $paginas = $this->paginaModel->findAll();
            foreach ($paginas as $pagina) {
                $permissoes = $this->permissaoModel->getByIdPaginaAndIdPerfil($pagina['id'], $perfil['id']);
                if (count($permissoes) > 0 ) {
                    foreach ($permissoes as $permissao) {
                        $result[] = [
                            'paginas_id' => $pagina['id'],
                            'nome_amigavel' => $pagina['nome_amigavel'],
                            'nome_classe' => $pagina['nome_classe'],
                            'metodos' => $this->metodoModel->getByPaginasId($pagina['id']),
                            'regras' => $permissao['regras'],
                            'id_permissao' => $permissao['id'],
                        ];
                    }
                } else {
                    $result[] = [
                        'paginas_id' => $pagina['id'],
                        'nome_amigavel' => $pagina['nome_amigavel'],
                        'nome_classe' => $pagina['nome_classe'],
                        'metodos' => $this->metodoModel->getByPaginasId($pagina['id']),
                        'regras' => '',
                        'id_permissao' => '',
                    ];
                }
            }
        } else {
            return redirect()->to('/mensagem/erro')->with('mensagem', 'Perfil não encontrado');
        }

        $data = [
            'titulo' => 'Edição de Perfil',
            'paginas' => $result,
            'perfil' => $perfil,
            'chave' => $chave
        ];

        echo view('perfis/form', $data);
    }

    public function store() {
        $post = $this->request->getPost();

        $this->perfilModel->transStart();

            if ($this->perfilModel->save($post)) { 
                if (empty($post['chave'])) {
                    $idPerfil = $this->perfilModel->getInsertID();
                } else {
                    $idPerfil = $this->perfilModel->getByChave($post['chave'])['id'];
                }
                foreach ($post['permissoes'] as $idPagina => $regras) {
                    // Se houver id_permissão é edição, então atualiza, senão é criação, daí insere
                    if (array_key_exists('id_permissao', $regras) && !empty($regras['id_permissao'])) {
                        foreach ($regras['id_permissao'] as $idPermissao => $regrasEdicao) {
                            $data = [
                                'id' => $idPermissao,
                                'paginas_id' => $idPagina,
                                'perfis_id' => $idPerfil,
                                'regras' => implode(',', $regrasEdicao),
                            ];
                        }
                    } else {
                        // Atualiza pois não foi passado o id_permissao
                        $data = [
                            'paginas_id' => $idPagina,
                            'perfis_id' => $idPerfil,
                            'regras' => implode(',', $regras),
                        ];
                    }
                    $this->permissaoModel->save($data);
                }
                $this->perfilModel->transComplete();
                $mensagens = !empty($post['chave']) ? 'Perfil editado com sucesso.' : 'Perfil criado com sucesso.';
                return redirect()->to('/mensagem/sucesso')->with('mensagem', [ 
                    'mensagem' => $mensagens,
                    'link' => [ 
                        'to' => 'perfil', 
                        'texto' => 'Voltar para perfis'
                    ]
                ]);
            } else {
                $paginas = $this->paginaModel->findAll();
                $result = [];
                foreach ($paginas as $pagina) {
                    $result[] = [
                        'paginas_id' => $pagina['id'],
                        'nome_amigavel' => $pagina['nome_amigavel'],
                        'nome_classe' => $pagina['nome_classe'],
                        'metodos' => $this->metodoModel->getByPaginasId($pagina['id']),
                        'regras' => '',
                        'id_permissao' => '',
                    ];
                }

                echo view('perfis/form', [
                    'titulo' => !empty($post['chave']) ? 'Editar Perfil' : 'Novo Perfil',
                    'paginas' => $result,
                    'errors' => $this->perfilModel->errors(),
                ]);
            }
    }

    // Apaga o registro pela sua chave
    public function delete($chave) {
        $this->perfilModel
            ->addUserId($this->session->id_usuario)
            ->delete($chave);
        return redirect()->to('/mensagem/sucesso')->with('mensagem', 'Perfil excluído com sucesso.');
    }
}
