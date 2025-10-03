<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="<?php echo base_url('assets/jquery/jquery-3.4.1.min.js') ?>"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/sbadmin/styles.css') ?>" Cache-Control: max-age=86400>
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" Cache-Control: max-age=86400>
    <script src="<?php echo base_url('assets/sbadmin/font-awesome.all.min.js') ?>"></script>

    <script>
        var base_url = "<?php echo base_url(); ?>"
    </script>
    <title>CI4 - PHP Money - Área Administrativa</title>
    <style>
        body {
            padding-top: 56px;
        }
    </style>
    <script>
        /*!
         * Start Bootstrap - SB Admin v6.0.1 (https://startbootstrap.com/templates/sb-admin)
         * Copyright 2013-2020 Start Bootstrap
         * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
         */
    </script>
</head>

<body class="sb-nav-fixed">

    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="<?php echo base_url('admin/home') ?>">PHP Money - Área Administrativa</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <?php echo anchor('login/signout', 'Sair', ['class' => 'nav-link']) ?>
            </li>
        </ul>
    </nav>
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-sm-2" style="height: 90vh;">
                <nav class="sb-sidenav accordion sb-sidenav-dark">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="<?php echo base_url() ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Voltar para o site
                            </a>
                            <a class="nav-link" href="<?php echo base_url('admin/home') ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Admin
                            </a>
                        </div>
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Páginas</div>
                            <a class="nav-link" href="<?php echo base_url('admin/pagina') ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Páginas Internas
                            </a>
                        </div>
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Usuários</div>
                            <a class="nav-link" href="<?php echo base_url('admin/usuario') ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Usuários Cadastrados
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logado como:</div>
                        <?php echo session()->nome_usuario ?>
                    </div>
                </nav>
            </div>
            <div class="col-sm-9 p-3">
                <?php echo $this->renderSection('content'); ?>
            </div>

        </div>
    </div>
    <!-- <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
        </div>
        <div id="layoutSidenav_content" class="p-3">
        </div>
    </div> -->

    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
</body>

</html>