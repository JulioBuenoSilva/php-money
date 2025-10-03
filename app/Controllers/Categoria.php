<?php

namespace App\Controllers;

use App\Models\CategoriaModel;

class Categoria extends BaseController
{
    protected $categoriaModel;

    public function __construct(){
        $this->categoriaModel = new CategoriaModel();
        $this->session = \Config\Services::session(); 
    }
    
    public function index()
    {
        $search = $this->request->getGet('search/');

        $categorias = $this->categoriaModel
            ->addUserId($this->session->id_usuario)
            ->addSearch($search)
            ->addOrder([ 
                'order' => [
                    [
                        'campo' => 'tipo',
                        'sentido' => 'desc' 
                    ],
                    [
                        'campo' => 'descricao',
                        'sentido' => 'desc'
                    ]
                ]
            ])
            ->paginate(5);


        $data = [
            'categorias' => $categorias,   
            'pager' => $this->categoriaModel->pager,
            'search' => $search
        ];

        return view('categorias/index', $data);
    }

    // chama o formulario de criacao
    public function create() 
    {
        $data = [
            'titulo' => 'Nova Categoria'
        ];
        echo view('categorias/form', $data);
    }

    // salva os dados vindos do formulário
    public function store() {
        $post = $this->request->getPost();
        if ($this->categoriaModel->save($post)) {
            return redirect()->to('mensagem/sucesso')->with('mensagem', [
                'mensagem' => 'Registro salvo com sucesso',
                'link' => [
                    'to' => 'categoria',
                    'texto' => 'Voltar para Categoria'
                ]]);
        }
        else {
            echo view('categorias/form', [
                'titulo' => !empty($post['chave']) ? 'Editar Categoria' : 'Nova Categoria',
                'errors' => $this->categoriaModel->errors()
            ]);
        }

    }

    // Chama o formulário de edição com os campos populados
    public function edit($chave) {
        $categoria = $this->categoriaModel->addUserId($this->session->id_usuario)->getByChave($chave);
        // dd($categoria);
        
        if (!is_null($categoria)) {
            $data = [
                'titulo' => 'Editar Categoria',
                'categoria' => $categoria
            ];
            echo view('categorias/form', $data);
        } else {
            return redirect()->to('/mensagem/erro')->with('mensagem', [
                'mensagem' => 'Categoria não encontrada',
                'link' => [
                    'to' => 'categoria',
                    'texto' => 'Voltar para Categoria'
                ]
            ]);
        }
    } 

    // Deletando categorias
    public function delete($chave) {
        if ($this->categoriaModel->addUserId($this->session->id_usuario)->delete($chave)) {
            return redirect()->to('/mensagem/sucesso')->with('mensagem', [
                'mensagem' => 'Categoria excluída com sucesso.',
                'link'  => [
                    'to' => 'categoria',
                    'texto' => 'Voltar para Categoria'
                ]
            ]);
        }
        else {
            return redirect()->to('/mensagem/erro')->with('mensagem', [
                'mensagem' => 'Erro ao excluir categoria',
                'link' => [
                    'to' => 'categoria',
                    'texto' => 'Voltar para Categoria'
                ]
            ]);
        }
    }
}