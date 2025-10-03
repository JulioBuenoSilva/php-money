<?php

namespace App\Controllers;

use Dompdf\Dompdf;

class Relatorio extends BaseController
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

    // chama a view principal
    public function index() {
        $data = [
            'countLancamentos' => 0,
            'dropDownCategorias' => $this->categoriaModel
                ->addOrder([
                    'campo' => 'descricao',
                    'sentido' => 'asc'
                ])
                ->addUserId($this->session->id_usuario)
                ->formDropDown(),
            ];
        echo view('relatorio/index', $data);
    }

    public function getDados()
    {
        $dadosRecebidos = $this->request->getGet();
        $descricao = $dadosRecebidos['descricao'] ?? null;
        $categorias_id = $dadosRecebidos['categorias_id'] ?? null;
        $dataInicial = !is_null($dadosRecebidos['dataInicial']) ? toDataEUA($dadosRecebidos['dataInicial']) : null;
        $dataFinal = !is_null($dadosRecebidos['dataFinal']) ? toDataEUA($dadosRecebidos['dataFinal']) : null;
        $tipo = $dadosRecebidos['tipo'] ?? null;
        $consolidado = $dadosRecebidos['consolidado'] ?? null;
        $tipoByCategoria = $this->categoriaModel->getTipoByCategoria($categorias_id) ?? null;

        $dataInicial = !is_null($dataInicial) ? "'{$dataInicial}'" : null;
        $dataFinal = !is_null($dataFinal) ? "'{$dataFinal}'" : null;
        
        if (!is_null($descricao)) {
            $this->categoriaModel
                ->groupStart()
                ->addSearch($descricao, 'categorias.descricao', true)
                ->addSearch($descricao, 'lancamentos.descricao', true)
                ->groupEnd();
        }

        $categorias = $this->categoriaModel
            ->addUserId($this->session->id_usuario)
            ->addConsolidado($consolidado)
            ->addDatas($dataInicial, $dataFinal)
            ->addTipo($tipo)
            ->addIdCategoria($categorias_id)
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

            $lancamentoQuery = (clone $this->lancamentoModel);
            if (!is_null($descricao)) {
                $lancamentoQuery
                    ->groupStart()
                    ->addSearch($descricao, 'categorias.descricao')
                    ->addSearch($descricao, 'lancamentos.descricao', true)
                    ->groupEnd();
            }
 
            $lancamentos = $lancamentoQuery
                ->addUserId($this->session->id_usuario)
                ->addConsolidado($consolidado)    
                ->addTipo($tipo)
                ->addTableCategorias()
                ->addDatas($dataInicial, $dataFinal)
                ->getByIdCategoria((int)$categoria['id_categoria']);
        
            if (!is_null($descricao)) {
                (clone $this->lancamentoModel)
                        ->groupStart()
                        ->addSearch($descricao, 'categorias.descricao')
                        ->addSearch($descricao, 'lancamentos.descricao', true)
                        ->groupEnd();
            }
            $totalPorCategoria = (clone $this->lancamentoModel)
                ->addUserId($this->session->id_usuario)
                ->addTableCategorias()
                ->addConsolidado($consolidado)
                ->addDatas($dataInicial, $dataFinal)
                ->addIdCategoria((int)$categoria['id_categoria'])
                ->getTotais();

            $resultCategorias[] = [
                'descricao' => $categoria['descricao_categoria'],
                'lancamentos' => $lancamentos,
                'totalPorCategoria' => $totalPorCategoria
            ];

            $lancamentoQuery = null;
            $countLancamentos += count($lancamentos);  
        }

        $tipo = $tipoByCategoria ?? $tipo;
        $totalReceitas =  $totalDespesas = 0;
        
        $lancamentoQuery = (clone $this->lancamentoModel);

        if (!is_null($descricao)) {
            $lancamentoQuery = $lancamentoQuery
                        ->groupStart()
                        ->addSearch($descricao, 'categorias.descricao')
                        ->addSearch($descricao, 'lancamentos.descricao', true)
                        ->groupEnd();
        }

        $totalReceitas = $lancamentoQuery
            ->addUserId($this->session->id_usuario)
            ->addDatas($dataInicial, $dataFinal)
            ->addConsolidado($consolidado)
            ->addIdCategoria($categorias_id)
            ->addTipo('r')
            ->addTableCategorias()
            ->getTotais();
        
        $lancamentoQuery = (clone $this->lancamentoModel);

        if (!is_null($descricao)) {
            $lancamentoQuery = $lancamentoQuery
                        ->groupStart()
                        ->addSearch($descricao, 'categorias.descricao')
                        ->addSearch($descricao, 'lancamentos.descricao', true)
                        ->groupEnd();
        }
        $totalDespesas = $lancamentoQuery
            ->addUserId($this->session->id_usuario)
            ->addDatas($dataInicial, $dataFinal)
            ->addConsolidado($consolidado)
            ->addIdCategoria($categorias_id)
            ->addTipo('d')
            ->addTableCategorias()
            ->getTotais();



        $dados = [
            'dropDownCategorias' => $this->categoriaModel
                ->addOrder([
                    'campo' => 'descricao',
                    'sentido' => 'asc'
                ])
                ->addUserId($this->session->id_usuario)
                ->formDropDown(),
            'categorias_id' => $categorias_id,
            'categorias'        => $resultCategorias,
            'totalReceitas'  => $totalReceitas,
            'totalDespesas'  => $totalDespesas,
            'saldo'     => (float) $totalReceitas - (float) $totalDespesas,
            'countLancamentos'  => $countLancamentos,
            'consolidado' => $consolidado,
            'dataInicial' => toDataBR(str_replace(["'", '"'], '', $dataInicial)),
            'dataFinal'   => toDataBR(str_replace(["'", '"'], '', $dataFinal)),
            'tipo' => $tipo,
            'descricao' => $descricao,
        ];

        if (isset($dadosRecebidos['tipo_impressao'])) {
            $view = view('relatorio/output', $dados);
            if($dadosRecebidos['tipo_impressao'] === 'pdf') {

                $nomeArquivo = 'Relatorio_' . toDataBR(str_replace(["'", '"'], '', $dataInicial)) . '_a_' . toDataBR(str_replace(["'", '"'], '', $dataFinal)) . '.pdf';
                $dompdf = new Dompdf();
                $dompdf->loadHtml($view);
                $dompdf->render();
                $dompdf->stream($nomeArquivo, ['Attachment' => true]);
            } elseif($dadosRecebidos['tipo_impressao'] = 'csv') {
                return $this->geraCSV($dados);
            }
        }
        echo view('relatorio/index', $dados);

    }

    // gera um arquivo csv para download 
    protected function geraCSV($dados) {
        helper('filesystem');
        $result = 'DESCRICAO;DATA;TIPO;CONSOLIDADO?;VALOR' . PHP_EOL;
        foreach ($dados['categorias'] as $categoria) {
            $result .= $categoria['descricao'] . PHP_EOL; 
            foreach ($categoria['lancamentos'] as $lancamento) {
                $result .= 
                    $lancamento['descricao'] . ';'
                    . $lancamento['data'] . ';'
                    . $lancamento['tipo_formatado'] . ';'
                    . $lancamento['consolidado_formatado'] . ';'
                    . number_format($lancamento['valor'], 2, ',', '.') . PHP_EOL;
                $result .= ';;;Subtotal:;' . number_format($categoria['totalPorCategoria'], 2, ',', '.') . PHP_EOL;
            }
        }
        $result .= 'Total de Receitas: ;' . number_format($dados['totalReceitas'], 2, ',', '.') . PHP_EOL;

        $result .= 'Total de Despesas: ;' . number_format($dados['totalDespesas'], 2, ',', '.') . PHP_EOL;

        $result .= 'Saldo: ;' . number_format($dados['saldo'], 2, ',', '.') . PHP_EOL;

        $path = WRITEPATH . 'relatorios';
        if (!file_exists($path)) {
            mkdir($path, 0777, true); 
        }
        $dataInicial = str_replace(["'", '"', '/'], ['','','-'], $dados['dataInicial']); 
        $dataFinal = str_replace(["'", '"', '/'], ['','','-'], $dados['dataFinal']); 
        if (!empty($dataInicial) && !empty($dataFinal)) {
            $nomeArquivo = 'Relatorio_de' 
                . $dataInicial
                . '_a_' 
                . $dataFinal 
                . '.csv';
            
        } elseif (!empty($dataInicial) && empty($dataFinal)) {
            $nomeArquivo = 'Relatorio_a_partir_de' 
                . $dataInicial
                . '.csv';
        } elseif (empty($dataInicial) && !empty($dataFinal)) {
            $nomeArquivo = 'Relatorio_ate_'
                . $dataFinal
                . '.csv';
        } else {
            $nomeArquivo = 'Relatorio';
        }

        $fullPath = $path . DIRECTORY_SEPARATOR . $nomeArquivo;

        if (!write_file($fullPath, $result)) {
            return redirect()->to('/mensagem/erro')
                ->with('mensagem', 'ERRO: Não foi possível gravar o arquivo');
        } else {
            return $this->response->download($fullPath, null);
        }

    }
}
