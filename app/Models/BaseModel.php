<?php 

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model 
{

    // faz a conversão do formato do valor, de BR para US
    protected function corrigeValor($data) {
        if (!isset($data['data']['valor'])) {
            return $data;
        }

        $data['data']['valor'] = str_replace('.', '', $data['data']['valor']);
        $data['data']['valor'] = str_replace(',', '.', $data['data']['valor']);
        return $data;
    }

    //vincula o id do usuario logado
    protected function vinculaIdUsuario($data) 
    {
        // ID fake para desenvolvimento
        session()->set('id_usuario', 1);

        $data['data']['usuarios_id'] = session()->id_usuario;

        return $data;
    } 

    // gera uma chave randomica e vincula ao campo da tabela
    protected function geraChave($data) 
    {
        $data['data']['chave'] = md5(uniqid(rand(), true));
        return $data;
    }

    protected function updateIdsFilhos() {
        // to do 
    }

    public function addUserId($id_usuario = null): object
    {
        if (!is_null($id_usuario)) {
            $this->where("{$this->table}.usuarios_id", $id_usuario);
        }
        return $this;
    }

    // injeta um parâmetro de busca na query, se o parâmetro $or for true, faz a busca por orLike
    public function addSearch($search = null, $campo = null, $or = false): object
    {
        if (!is_null($search) && !is_null($campo)) {
            if ($or) {
                $this->orLike($campo, $search);
            } else {
                $this->like($campo, $search);
            }
        }
        return $this;
    }

    public function addOrder($order = null): object
    {
        if (key_exists('order', $order)) {
            foreach ($order['order'] as $o) {
                $this->orderBy($o['campo'], $o['sentido']);
            }
        } else {
            $this->orderBy($order['campo'], $order['sentido']);
        }
        return $this;
    }

    // injeta o campo tipo na query de busca
    public function addTipo($tipo = null)
    {
        $this->addTableCategorias();
        if (!empty($tipo)) {
            $this->where('tipo', $tipo);
        }
        return $this;
    }

    
    // retorna os registros baseado na informação de consolidação
    // é preciso que a tabela lançamentos exista na query
    // valor 1 = Sim, 2 = Não
    public function addConsolidado($valor = null) {
        if (!empty($valor)) {
            $this->where('lancamentos.consolidado', $valor);
        }
        return $this;
    }

    // filtra os registros por mês
    public function addMes($mes = null) {
        if (!is_null($mes)) {
            $this->where("MONTH(data)", $mes);
        }
        return $this;
    }

    // filtra os registros por ano
    public function addAno($ano = null) {
        if (!is_null($ano)) {
            $this->where("YEAR(data)", $ano);
        }
        return $this;
    }

    // injeta a busca por id da categoria dentro da query
    public function addIdCategoria($idCategoria = null) {
        if (!empty($idCategoria)) {
            $this->where('categorias_id', $idCategoria);
        }
        return $this;
    }
    
    // injeta o filtro por datas na query
    public function addDatas($dataInicial = null, $dataFinal = null): object
    {
        if (!is_null($dataInicial)) {
            $this->where("data >= {$dataInicial}");
        } 
        if (!is_null($dataFinal)) {
            $this->where("data <= {$dataFinal}");
        }

        return $this;
    }

    // injeta a busca por chave dentro da query
    public function getByChave($chave = null) {
        if (!is_null($chave)) {
            $retorno = $this->where('chave', $chave)->first(); 
            return $retorno;
        }
    }

    // retorna os dados por id
    public function getById($id = null) {
        if (!is_null($id)) {
            $retorno = $this->where('id', $id)->first(); 
            return $retorno;
        }
    }  

    // retorna todos os registros da consulta
    public function getAll(): array {
        return $this->findAll();
    }

    // Verifica se o registro pertence ao dono ou a um familiar
    protected function checaPropriedade($data) {
        if (!isset($data['data']['chave'])) {
            return $data;
        } 

        $idProprietario = $this->getByChave($data['data']['chave'])['usuarios_id'];
        
            // Se o id do proprietário não for nem do usuário logado nem de um de seus descendentes
            if ($idProprietario != session()->id_usuario && !in_array($idProprietario, session()->ids_filhos)) {
                session()->setFlashdata('mensagem', '[NEGADO] - Você não tem acesso a este registro');
                header('location: /mensagem/erro');
                die();
            }
        return $data;
    }

    // converte a data para o formato americano
    protected function converteData($data) {
        if (is_null($data['data']['data'])) {
            return $data;
        }
        $data['data']['data'] = toDataEUA($data['data']['data']);
        return $data;
    }

}
?>