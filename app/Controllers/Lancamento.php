<?php

namespace App\Controllers;

class Lancamento extends BaseController
{
    protected $categoriaModel;
    protected $lancamentoModel;
    protected $orcamentoModel;

    public function __construct()
    {
        $this->categoriaModel = new \App\Models\CategoriaModel();
        $this->lancamentoModel = new \App\Models\LancamentoModel();
        $this->orcamentoModel = new \App\Models\OrcamentoModel();
        helper(['funcoes']);
    }

    public function index($mes = null, $ano = null)
    {
        $post = $this->request->getPost();
        $search = $this->request->getGet('search') ?? '';

        $ano = $post['ano'] ?? $ano ?? date('Y');
        $mes = $post['mes'] ?? $mes ?? date('n');

        if (empty($search)) {
            $this->categoriaModel
            ->addMes($mes)
            ->addAno($ano);
        }

        $categorias = $this->categoriaModel            
            ->groupStart()
            ->addSearch($search, 'categorias.descricao', true)
            ->addSearch($search ?? null, 'lancamentos.descricao', true)
            ->groupEnd()
            ->addUserId($this->session->id_usuario)
            ->addOrder([
                'order' => [
                    [
                        'campo' => 'tipo',
                        'sentido' => 'desc'
                    ],
                    [
                        'campo' => 'descricao_categoria',
                        'sentido' => 'asc'
                    ]
                ]
            ])
            ->getComLancamentos();

        // buscar os lancamentos de cada categoria
        $resultCategorias = [];
        $countLancamentos = 0;
        foreach ($categorias as $categoria){
            if (empty($search)) {
                $this->lancamentoModel
                ->addMes($mes)
                ->addAno($ano);
            }
            
            $lancamentos = $this->lancamentoModel
                ->groupStart()
                ->addSearch($search, 'categorias.descricao', true)
                ->addSearch($search, 'lancamentos.descricao', true)
                ->groupEnd()
                ->addUserId($this->session->id_usuario)
                ->getByIdCategoria($categoria['id_categoria']);
        
            $valorOrcamento = $this->orcamentoModel
                ->addUserId($this->session->id_usuario)
                ->valorOrcamento($categoria['id_categoria']);

            if (empty($search)) {
                $this->lancamentoModel
                ->addMes($mes)
                ->addAno($ano);
            }
            
            $totalPorCategoria = $this->lancamentoModel
                ->addUserId($this->session->id_usuario)
                ->addConsolidado(1)
                ->addIdCategoria($categoria['id_categoria'])
                ->getTotais();

            $resultCategorias[] = [
                'descricao' => $categoria['descricao_categoria'],
                'lancamentos' => $lancamentos,
                'totalPorCategoria' => $totalPorCategoria,
                'valorOrcamento' => $valorOrcamento,
                'orcamentoDisponivel' => (float) $valorOrcamento - (float) $totalPorCategoria
            ];

            $countLancamentos += count($lancamentos);  
        }
        
        $receitasTotalGeral = $this->lancamentoModel
            ->addUserId($this->session->id_usuario)
            ->addConsolidado(1)
            ->addTipo('r')
            ->getTotais();

        $despesasTotalGeral = $this->lancamentoModel
            ->addUserId($this->session->id_usuario)
            ->addConsolidado(1)
            ->addTipo('d')
            ->getTotais();
        
        $saldoTotalGeral = (float) $receitasTotalGeral - (float) $despesasTotalGeral;

        $receitasMesAtual =  $despesasMesAtual = 0;
        if (empty($search)) {
            $receitasMesAtual = $this->lancamentoModel
                ->addUserId($this->session->id_usuario)
                ->addConsolidado(1)
                ->addMes($mes)
                ->addAno($ano)
                ->addTipo('r')
                ->getTotais();
            
            $despesasMesAtual = $this->lancamentoModel
                ->addUserId($this->session->id_usuario)
                ->addConsolidado(1)
                ->addMes($mes)
                ->addAno($ano)
                ->addTipo('d')
                ->getTotais();
        }
        
        $dataReferencia = date('Y-m-t', strtotime("{$ano}-{$mes}-01"));
        $saldoMesAnterior = $this->lancamentoModel->getSaldoAnterior($dataReferencia);

        $dados = [
            'ano'           => $ano ?? date('Y'),
            'mes'           => $mes ?? date('m'),
            'comboAnos'     => comboAnos([
                'anoInicial' => $this->lancamentoModel
                    ->addUserId($this->session->id_usuario)
                    ->getMenorAno(),
            ]),
            'categorias'        => $resultCategorias,
            'receitas'          => $receitasTotalGeral,
            'despesas'          => $despesasTotalGeral,
            'saldo'             => $saldoTotalGeral,
            'receitasMesAtual'  => $receitasMesAtual,
            'despesasMesAtual'  => $despesasMesAtual,
            'saldoMesAtual'     => (float) $receitasMesAtual - (float) $despesasMesAtual + $saldoMesAnterior,
            'saldoMesAnterior'  => $saldoMesAnterior,
            'countLancamentos'  => $countLancamentos,
            'search'            => $search
        ];

        return view('lancamentos/index', $dados);
    }

    // carrega o formulário de criação de lançamento
    public function create(){

        $data = [
            'titulo' => 'Novo Lancamento',

            'dropDownCategorias' => $this->categoriaModel
                ->addUserId($this->session->id_usuario)
                ->addOrder([
                    'campo' => 'descricao',
                    'sentido' => 'asc'
                ])
                ->formDropDown([
                    'opcaoNova' => true
                ])
        ];

        echo view('lancamentos/form', $data);
    }

    public function store() {
        $post = $this->request->getPost();
        $dataLancamento = strtotime(toDataEUA($post['data']));
        $hoje = strtotime(date('Y-m-d'));

        // se a data do lançamento for futura, o lançamento será não-consolidado
        if ($dataLancamento > $hoje) {
            $post['consolidado'] = 2;
        }
        if ($this->lancamentoModel->save($post)) {
            return redirect()->to('/mensagem/sucesso')->with('mensagem', 
                [ 
                    'mensagem' => 'Registro salvo com sucesso',
                    'link' => [ 
                        'to' => 'lancamento', 
                        'texto' => 'Voltar para lançamentos'
                    ]
                ]
            );
        } else {
            $dados = [ 
                'titulo' => !empty($post['chave']) ? 'Editar lançamento' : 'Novo lançamento',
                'errors' => $this->lancamentoModel->errors(),
                'formDropDown' => $this->categoriaModel
                ->addOrder([
                    'campo' => 'descricao',
                    'sentido' => 'asc'
                ])
                ->addUserId($this->session->id_usuario)
                ->formDropDown([
                    'opcaoNova' => 'true' 
                ])
            ];

            echo view('/lancamentos/form', $dados);
        }
    }

    public function edit($chave) {
        $lancamento = $this->lancamentoModel
            ->addUserId($this->session->id_usuario)
            ->getByChave($chave);

        if (!is_null($lancamento)) {
            echo view('lancamentos/form', [
                'titulo' => 'Editar lançamento',
                'lancamento' => $lancamento,
                'dropDownCategorias' => $this->categoriaModel
                    ->addOrder([
                        'campo' => 'descricao',
                        'sentido' => 'asc'
                    ])
                    ->addUserId($this->session->id_usuario)
                    ->formDropDown([
                        'opcaoNova' => 'true' 
                    ])
            ]);
        } else {
            return redirect()->to('/mensagem/erro')->with('mensagem', [
                'mensagem' => 'ERRO - Lançamento não encontrado',
                'link' => [
                    'to' => 'lancamento',
                    'texto' => 'Voltar para lançamentos'
                ] 
            ]);
        }

    
    }

    public function delete($chave) {
        if ($this->lancamentoModel->addUserId($this->session->id_usuario)->delete($chave)) {
            return redirect()->to('/mensagem/sucesso')->with('mensagem', [
                'mensagem' => 'Lançamento excluído com sucesso',
                'link' => [
                    'to' => 'lancamento',
                    'texto' => 'Voltar para Lançamentos'
                ]
            ]);
        } else {
            return redirect()->to('/mensagem/erro')->with('mensagem', [
                'mensagem' => "Erro ao excluir lanrçamento",
                'link' => [
                    'to' => 'lancamento',
                    'texto' => 'Voltar para Lançamentos'
                ]
            ]);
        }
    }
}
