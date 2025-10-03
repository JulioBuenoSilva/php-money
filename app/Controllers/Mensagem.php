<?php namespace App\Controllers;

class Mensagem extends BaseController {
    public function sucesso() {
        $mensagem = $this->session->getFlashdata('mensagem');
        
        if (is_array($mensagem)) {
            echo view('_common/mensagens/sucesso', [
                'mensagem' => $mensagem['mensagem'],
                'link' => anchor($mensagem['link']['to'], $mensagem['link']['texto'], ['class' => 'nav-link'])
            ]);
        } else {
            echo view('_common/mensagens/sucesso', [
                'mensagem' => $mensagem,
                'link' => "<a href='javascript:history.back()' class='nav-link'>Voltar</a>"
            ]);
        }
    }

    public function erro() {
        $mensagem = $this->session->getFlashdata('mensagem');
        
        if (is_array($mensagem)) {
            echo view('_common/mensagens/erro', [
                'mensagem' => $mensagem['mensagem'],
                'link' => anchor($mensagem['link']['to'], $mensagem['link']['texto'], ['class' => 'nav-link'])
            ]);
        } else {
            echo view('_common/mensagens/erro', [
                'mensagem' => $mensagem,
                'link' => anchor(base_url(), 'Pagina Inicial', ['class' => 'nav-link'])
            ]);
        }
    }
}
?>