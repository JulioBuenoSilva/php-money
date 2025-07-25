<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Money - Sistema de Controle Financeiro Simples</title>
    <?= link_tag('assets/bootstrap/css/bootstrap.min.css')?>
    <style>
        .logo_siste {
            height: 40px;
            margin: 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a href="<?= base_url()?>" class="navbar-brand">
            <img src="<?= base_url('assets/imagens/logo_php_exp_white.png')?>" alt="logo do site">
        </a>
        <button class="navbar-toggler" type="button" data-toggler="collapse" data-target="#navbarNavDropwdwn">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdwn">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a href="<?= base_url()?>" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="<?= base_url('lancamento')?>" class="nav-link">Lançamentos</a></li>
                <li class="nav-item"><a href="<?= base_url('categoria')?>" class="nav-link">Categorias</a></li>
                <li class="nav-item"><a href="<?= base_url('orcamento')?>" class="nav-link">Orçamentos</a></li>
                <li class="nav-item"><a href="<?= base_url('relatorio')?>" class="nav-link">Relatório</a></li>            
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggler="dropdown" aria-haspopup="true">Configuracoes</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="<?=base_url('perfil')?>">Perfis</a>
                        <a class="dropdown-item" href="<?=base_url('usuario')?>">Usuarios</a>
                    </div>
                </li>            
                <?php if (session()->isLoggedIn) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('login/signout') ?>">Sair</a>
                    </li>
                <?php endif?>
            </ul>
        </div>
    </nav>
    <div class="container" role="main">
        <?= $this->renderSection('content') ?>
    </div>
    <script src="<?= base_url('assets/jquery/jquery-3.2.1.slim.min.js')?>"></script>
    <script src="<?= base_url('assets/bootstrap/javascript/bootstrap.min.js')?>"></script>
</body>
</html>