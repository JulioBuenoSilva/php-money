<?php

namespace App\Models;

use DateTime;

class LancamentoModel extends BaseModel
{
    protected $table = 'lancamentos';

    protected $primaryKey = 'chave';

    protected $useSoftDeletes = false; 
    
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $beforeInsert = ['vinculaIdUsuario', 'geraChave', 'corrigeValor', 'converteData'];
    protected $beforeUpdate = ['converteData', 'corrigeValor', 'checaPropriedade'];

    protected $useTimestamps = true;


    protected $allowedFields = [
        'usuarios_id',
        'chave',
        'categorias_id',
        'descricao',
        'valor',
        'data',
        'notificar_por_email',
        'consolidado',
    ];

    protected $validationRules = [
        'descricao' => [
            'label' => 'Descrição',
            'rules' => 'required'
        ],
        'categorias_id' => [
            'label' => "Categoria",
            'rules' => 'required'
        ],
        'valor' => [
            'label' => "Valor",
            'rules' => 'required'
        ],
        'data' => [
            'label' => "Data",
            'rules' => 'required'
        ],
    ];

    // busca todos os lancamentos de uma determinada categoria
    public function getByIdCategoria($idCategoria) 
    {
        $this->addTableCategorias();

        $this->select("
            lancamentos.id AS id_lancamento,
            lancamentos.created_at,
            lancamentos.usuarios_id,
            categorias.tipo,
            categorias.descricao AS descricao_categoria,
            IF(categorias.tipo = 'r', 'Receita', 'Despesa') AS tipo_formatado,
            lancamentos.descricao,
            lancamentos.data,
            lancamentos.categorias_id,
            lancamentos.notificar_por_email,
            IF(lancamentos.notificar_por_email = '1', 'Sim', 'Nao') AS notificar_formatado,
            lancamentos.valor,
            lancamentos.chave,
            lancamentos.consolidado,
            IF(lancamentos.consolidado = '1', 'Sim', 'Nao') AS consolidado_formatado
        ");
        $this->where('lancamentos.categorias_id', $idCategoria);
        return $this->findAll();
    }
    
    // soma os valores 
    public function getTotais(): float {
        $this->selectSum('valor');
        $result = $this->first();

        return !is_null($result['valor']) ? $result['valor'] : 0.00;
    }

    // retorna o menor ano com registro, se não houver nenhum, retorna o ano atual
    public function getMenorAno() {
        $result = $this->select('MIN(YEAR(data)) as menor_ano')->first();
        return !is_null($result['menor_ano']) ? $result['menor_ano'] : date('Y');
    }

    // calcula o saldo anterior a uma data, utilizando o lançamento mais antigo do mês anterior
    public function getSaldoAnterior(string $data) {
        $id_usuario = session()->id_usuario;

        $dataReferencia = new DateTime($data);
        $dataAnterior = $dataReferencia->modify('last day of last month')->format('Y-m-d');
        $dataInicial = $this->addUserId($id_usuario)->getMenorANo() . "-01-01";

        $this->selectSum('valor');
        $this->where("data BETWEEN '{$dataInicial}' AND '{$dataAnterior}'");
        $this->addTipo('d');
        $this->addConsolidado(1);
        $this->addUserId($id_usuario);
        $totalDespesas = (float) $this->first()['valor'];
    
        $this->resetQuery(true);
        $this->selectSum('valor');
        $this->where("data BETWEEN '{$dataInicial}' AND '{$dataAnterior}'");
        $this->addTipo('r');
        $this->addConsolidado(1);
        $this->addUserId($id_usuario);
        $totalReceitas = (float) $this->first()['valor'];

        return ($totalReceitas ?? 0) - ($totalDespesas ?? 0);
    }
        
    // injeta a tabela categorias quando necessário 
    public function addTableCategorias(): object
    {
        $this->join('categorias', 'categorias.id = lancamentos.categorias_id');
        $this->where('categorias.deleted_at IS NULL');
        return $this;
    }
}