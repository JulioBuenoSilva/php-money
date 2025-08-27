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

    public function addUserId($id_usuario = null): object
    {
        if (!is_null($id_usuario)) {
            $this->where("{$this->table}.usuarios_id", $id_usuario);
        }
        return $this;
    }

    // injeta um parâmetro de busca na query
    public function addSearch($search = null, $campo = null): object
    {
        if(!is_null($search) && !is_null($campo)) {
            $this->like($campo, $search)->findAll();
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
    public function addTipo($tipo = null) {
        if (!is_null($tipo)) {
            $this->where('tipo', $tipo);
        }
        return $this;
    }

    // injeta a busca por chave dentro da query
    public function getByChave($chave = null) {
        if (!is_null($chave)) {
            $retorno = $this->find($chave); 
            return $retorno;
        }
    }

    // retorna todos os registros da consulta
    public function getAll(): array {
        return $this->findAll();
    }

    // Verifica se o registro pertence ao dono ou a um familiar
    protected function checaPropriedade($data) {
        return $data;
    }

    // converte a data para o formato americano
    protected function converteData($data) {
        return $data;
    }
}
?>