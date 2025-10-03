<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Money - Sistema de Controle Financeiro Simples</title>
    <?= link_tag('assets/bootstrap/css/bootstrap.min.css')?>
    <script src="<?= base_url('assets/jquery/jquery-3.2.1.slim.min.js')?>"></script>
    <script src="<?= base_url('assets/jquery/jquery-3.5.1.min.js')?>"></script>
    <script src="<?= base_url('assets/jquery.mask/jquery.mask.js')?>"></script>
    <script>
        $(document).ready(function(){
            $('#valor').mask('000.000.000.000.000,00', {
                reverse: true
            });
        });

        var base_url = "<?=base_url()?>";
    </script>

    <style>
        .logo_sistema img {
            height: 40px; 
            margin: 0; 
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a href="<?= base_url()?>" class="navbar-brand logo_sistema">
            <img src="<?= base_url('assets/imagens/logo_php_exp_white.png')?>" alt="logo do site">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a href="<?= base_url()?>" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="<?= base_url('lancamento')?>" class="nav-link">Lançamentos</a></li>
                <li class="nav-item"><a href="<?= base_url('categoria')?>" class="nav-link">Categorias</a></li>
                <li class="nav-item"><a href="<?= base_url('orcamento')?>" class="nav-link">Orçamentos</a></li>
                <li class="nav-item"><a href="<?= base_url('relatorio')?>" class="nav-link">Relatório</a></li>          
                <li class="nav-item"><a href="<?= base_url('admin/home')?>" class="nav-link">Área Administrativa</a></li>          
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" 
                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Menu
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
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
    <div class="container pt-4 mt-5" role="main">
        <?= $this->renderSection('content') ?>
    </div>
    <script src="<?= base_url('assets/bootstrap/javascript/bootstrap.min.js')?>"></script>
</body>
</html>