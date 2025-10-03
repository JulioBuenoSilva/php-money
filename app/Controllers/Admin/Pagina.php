<?php

namespace App\Controllers\Admin;

use App\Models\PaginaModel;
use App\Models\MetodoModel;

class Pagina extends BaseController
{
    protected $paginaModel;
    protected $metodoModel;

    public function __construct()
    {
        $this->paginaModel = new PaginaModel();
        $this->metodoModel = new MetodoModel();
    }

    // chama a view principal
    public function index()
    {
        $dados = [
            'paginas' => $this->paginaModel
                ->addOrder([
                    'campo' => 'id',
                    'sentido' => 'asc'
                ])
                ->getAll()
        ];
        echo view('admin/paginas/index', $dados);
    }

    // chama a view de criação de nova página
    public function create() {
        echo view('admin/paginas/form', [
            'titulo' => 'Nova Página',
        ]);
    }

    // chama a view de edição de registro
    public function edit($chave) {
        $pagina = $this->paginaModel->getByChave($chave);
        if (!is_null($pagina)) {
            // Verifica se a página possui métodos cadastrados
            $path = '\App\Controllers\\' . $pagina['nome_classe'];
            $classExists = class_exists($path);
            
            if ($classExists) {
                $metodos = get_class_methods($path);
                $metodosIndesejados = get_class_methods('\App\Controllers\\BaseController');
                
                $metodosDesejados = array_diff($metodos, $metodosIndesejados);

                foreach ($metodosDesejados as $metodo) {
                    $metodosCompleto[] = [
                        'nome_metodo' => $metodo,
                        'nome_amigavel' => $this->metodoModel->getNomeAmigavel($pagina['id'], $metodo)
                    ]; 
                }

                echo view('admin/paginas/form', [
                    'titulo' => 'Editar Página',
                    'chave' => $chave,
                    'pagina' => $pagina,
                    'metodos' => $metodosCompleto,
                ]); 
            }
        } else {
            return redirect()->to('/mensagem/erro')->with('mensagem', 'Página não encontrada');
        }
    }

    // Salva no banco as páginas e seus respectivos métodos
    public function store() {
        $post = $this->request->getPost();
        
        if ($this->paginaModel->save($post)) {
            if (!empty($post['chave'])) {
                $dadosPagina = $this->paginaModel->getByChave($post['chave']);
                if (!is_null($dadosPagina)) {
                    $idPagina = $dadosPagina['id'];
                    // Se o formulário estiver passando novos métodos, reemovo todos os métodos já existentes para inseri-los novamente
                    if (isset($post['metodos'])) {
                        if ($this->metodoModel->deleteByPaginasId($idPagina)) {
                            foreach ($post['metodos'] as $nome_metodo => $nome_amigável) {
                                if (empty($nome_amigável)) {
                                    continue;
                                }

                                $dadosMetodos = [
                                    'paginas_id' => $idPagina,
                                    'nome_metodo' => $nome_metodo,
                                    'nome_amigavel' => $nome_amigável
                                ];
                                $this->metodoModel->insert($dadosMetodos);
                            }
                        } else {
                            return redirect()->to('/mensagem/erro')->with('mensagem', 'Erro ao atualizar métodos da página');
                        }
                    }
                    return redirect()->to("/admin/pagina/{$post['chave']}/edit")->with('mensagem', 'Registro salvo com sucesso');
                }
            } else {
                $idPagina = $this->paginaModel->getInsertID();
                $chavePagina = $this->paginaModel->getById($idPagina)['chave'];
                return redirect()->to("/admin/pagina/{$chavePagina}/edit")->with('mensagem', 'Registro salvo com sucesso');
            }
        } else {
            echo view('admin/paginas/form', [
                'titulo' => empty($post['chave']) ? 'Nova Página' : 'Editar Página',
                'chave' => $post['chave'],
                'errors' => $this->paginaModel->errors()
            ]);
        }
    }

    public function delete($chave) 
    {
        $this->paginaModel
            ->delete($chave);
        return redirect()->to('/admin/pagina')->with('mensagem', 'Registro excluído com sucesso');
    }
}