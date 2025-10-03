<?php

namespace App\Controllers;

use App\Models\OrcamentoModel;
use App\Models\CategoriaModel;

class Orcamento extends BaseController
{
    protected $orcamentoModel;
    protected $categoriaModel;

    public function __construct() {
        $this->orcamentoModel = new OrcamentoModel();
        $this->categoriaModel = new CategoriaModel();
    }

    public function index(): string
    {
        $search = $this->request->getGet('search');
        $orcamentos = $this->orcamentoModel
            ->addSearch($search, 'orcamentos.descricao')
            ->addUserId($this->session->id_usuario)
            ->addOrder([
                'campo' => 'orcamentos.descricao',
                'sentido' => 'asc'
            ])
            ->getAllWithCategorias();
            
        $data = [
            'orcamentos' => $orcamentos,
            'search' => $search
        ]; 

        return view('orcamentos/index', $data);
    }

    // chama a view do formulario
    public function create() {
        $data = [
            'titulo' => 'Novo orçamento',
            'formDropDown' => $this->categoriaModel
                ->addOrder([
                    'campo' => 'descricao',
                    'sentido' => 'asc'
                ])
                ->addUserId($this->session->id_usuario)
                ->formDropDown([
                    'opcaoNova' => true,
                    'tipo' => 'd'
                ])
        ];
        echo view('orcamentos/form', $data);
    }

    // salva um registro no bacno, se a chave vier junto atualiza
    public function store() {
        $post = $this->request->getPost();
        $post['usuarios_id'] = $this->session->id_usuario;
        
        if ($this->orcamentoModel->save($post)) {
            return redirect()->to('/mensagem/sucesso')->with('mensagem', [
                'mensagem' => 'Orçamento salvo com sucesso', 
                'link' => [
                    'to' => 'orcamento',
                    'texto' => 'Voltar para Orçamentos'
                ]
            ]);
        } else {
            $dados = [ 
                'titulo' => !empty($post['chave']) ? 'Editar orçamento' : 'Novo orçamento',
                'errors' => $this->orcamentoModel->errors(),
                'formDropDown' => $this->categoriaModel
                ->addOrder([
                    'campo' => 'descricao',
                    'sentido' => 'asc'
                ])
                ->addUserId($this->session->id_usuario)
                ->formDropDown([
                    'tipo' => 'd',
                    'opcaoNova' => 'true' 
                ])
            ];

            echo view('/orcamentos/form', $dados);
        }
    }

    // carrega o formulário de edição de orçamentos já com os campos populados
    public function edit($chave) {
        $orcamento = $this->orcamentoModel
            ->addUserId($this->session->id_usuario)
            ->getByChave($chave);
        
        if (!is_null($orcamento)) {
            echo view('orcamentos/form', [
                'titulo' => 'Editar Orcamento',
                'orcamento' => $orcamento,
                'formDropDown' => $this->categoriaModel
                    ->addOrder([
                        'campo' => 'descricao',
                        'sentido' => 'asc'
                    ])
                    ->formDropDown([
                        'tipo' => 'd',
                        'opcaoNova' => true
                    ])
            ]);
        } else {
            return redirect()->to('/mensagem/erro')->with('mensagem', [
                'mensagem' => "Orçamento Não Encontrado",
                'link' => [
                    'to' => 'orcamento',
                    'texto' => 'Voltar para Orçamentos'
                ]
            ]);
        }
    }

    public function delete($chave) {
        if ($this->orcamentoModel->addUserId($this->session->id_usuario)->delete($chave)) {
            return redirect()->to('/mensagem/sucesso')->with('mensagem', [
                'mensagem' => 'Orçamento excluído com sucesso',
                'link' => [
                    'to' => 'orcamento',
                    'texto' => 'Voltar para Orçamentos'
                ]
            ]);
        } else {
            return redirect()->to('/mensagem/erro')->with('mensagem', [
                'mensagem' => "Erro ao excluir orçamento",
                'link' => [
                    'to' => 'orcamento',
                    'texto' => 'Voltar para Orçamentos'
                ]
            ]);
        }
    }
}
