<?php
namespace App\Controllers\Ajax;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;

use function PHPUnit\Framework\isNull;

class Categoria extends BaseController
{
    protected $categoriaModel;

    public function __construct() {
        $this->categoriaModel = new CategoriaModel();
    }

    // salva a categoria recebida via AJAX no banco
    public function store() {
        $result = [];
        if ($this->request->isAJAX()) {
            $post = $this->request->getPost();
            if ($this->categoriaModel->save($post)) {
                $result = [
                    'error' => false,
                    'code' => 201,
                    'message' => 'created',
                    'id' => $this->categoriaModel->getInsertID()
                ];
            } else {
                $result = [
                    'error' => true,
                    'message' => $this->categoriaModel->errors(),
                ];
            }
        } else {
            $result = [
                'error' => true,
                'code' => 400,
                'message' => '[ERRO] - Somente requisições via AJAX permitidas'
            ];
        }
        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    // retorna todas as categorias do usuario logado
    // se for passado o parametro tipo filtra as categorias por tipo
    public function get() {
        if ($this->request->isAJAX()) {
            $result = [];
            $tipo = $this->request->getGet('tipo');
            if (!isNull($tipo)) {
                $this->categoriaModel->addTipo($tipo);
            }
            $result = $this->categoriaModel
            ->addUserId($this->session->id_usuario)
            ->addOrder([
                'campo' => 'descricao',
                'sentido' => 'asc'
            ])
            ->getAll();
        } else {
            $result = [
                'error' => true,
                'code' => 400,
                'message' => '[ERRO] - Somente requisições via AJAX permitidas'
            ];
        }
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
}