<?php 
namespace App\Libraries;
use \App\Controllers\Admin\BaseController;

class Security extends BaseController {
    protected static $usuarioModel;
    protected static $permissaoModel;
    protected static $paginaModel;
    protected static $id_usuario;

    public static function init() {
        self::$usuarioModel
    }
}