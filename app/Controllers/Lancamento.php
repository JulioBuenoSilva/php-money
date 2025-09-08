<?php

namespace App\Controllers;

class Lancamento extends BaseController
{
    protected $categoriaModel;
    protected $lancamentoModel;

    public function __construct()
    {
        parent::__construct(); 
        $this->categoriaModel = new \App\Models\CategoriaModel();
        $this->lancamentoModel = new \App\Models\LancamentoModel();
        helper(['funcoes']);
    }

    public function index($mes = null, $ano = null)
    {
        $post = $this->request->getPost();

        $ano = $post['ano'] ?? $ano ?? date('Y');
        $mes = $post['mes'] ?? $mes ?? date('n');

        $categorias = $this->categoriaModel
        ->addUserId($this->session->id_usuario)
        ->addMes($mes)
        ->addAno($ano)
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
            $lancamentos = $this->lancamentoModel
                ->addUserId($this->session->id_usuario)
                ->addMes($mes)
                ->addAno($ano)
                ->getByIdCategoria($categoria['id_categoria']);
        
            $totalPorCategoria = $this->lancamentoModel
                ->addUserId($this->session->id_usuario)
                ->addConsolidado(1)
                ->addMes($mes)
                ->addAno($ano)
                ->addIdCategoria($categoria['id_categoria'])
                ->getTotais();

            $resultCategorias[] = [
                'descricao' => $categoria['descricao_categoria'],
                'lancamentos' => $lancamentos,
                'totalPorCategoria' => $totalPorCategoria
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
            'countLancamentos'  => $countLancamentos
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
            return redirect()->to('/mensagens/sucesso')->with('mensagem', 
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
}
