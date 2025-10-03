<?php 
namespace App\Libraries;

class MinhasRegras {
    
    // Verifica se uma classe foi definida
    public function check_class_exists(string $className) :bool {
        $path = '\App\Controllers\\' . $className;
        return class_exists($path);
    }

    // Verifica se uma senha digitada pelo usuário está correta
    public function check_senha_atual(string $senha) {
        $usuarioModel = new \App\Models\UsuarioModel();

        $session = service('session');
        $chave = $session->get('chave');
        $dadosUsuario = $usuarioModel->getByChave($chave);
        return password_verify($senha, $dadosUsuario['senha']);
    }
}

?>