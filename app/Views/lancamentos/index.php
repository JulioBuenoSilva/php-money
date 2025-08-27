<?php echo $this->extend('_common/layout');
echo $this->section('content');?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <?= anchor('','Home')?></li>
        <li class="breadcrumb-item active" aria-current="pag"> Lançamentos</li>
    </ol>
</nav>

<div class="row no-gutters d-flex justify-content-center justify-content-sm-between">
    <div class="my-0">
        <h1> Lancamentos </h1>
    </div>
    <div class="text-center pt-3">
        <span class="text-success">Receitas Geral: </span>
        -
        <span class="text-danger">Despesas Geral: </span>
        =
        <span class="text-success">Saldo Geral: </span>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <div class="row justify-content-center justify-content-sm-start">
            <div class="text-center">
                Ultimos lançamentos - XXX - registros encontrados - <strong class="text-uppercase"> <?= date('m/Y') ?> </strong>
            </div>
            <div class="d-none d-sm-block">&nbsp; - &nbsp;</div>
            <div class="text-center"> <?= anchor('lancamento', 'Selecionar Hoje', ['title' => 'Retorna para a data atual']) ?> </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row no-gutters d-flex justify-content-center justify-content-sm-between">
            <div class="my-3">
                <?= anchor('lancamento/create', 'Novo Lançamento', ['class' => 'btn btn-primary', 'title' => 'Adicionar novo lançamento']) ?>
            </div>
            <?= form_open('lancamento', ['class' => 'form-inlie', 'method' => 'GET'])?>
                <div class="form-group d-flex justify-content-center justify-content-sm-between">
                    <input type="search" name="search" autocomplete="off" placeholder="Busca..." class="form-control" value="<?=  $search ?? '' ?>">
                    <input type="submit" value="OK" class="ml-2 btn btn-primary">
                </div>
            <?= form_close() ?>
        </div>
        <div class="row no-gutters d-flex justify-content-center">
            <div class="my-3">
                <?= form_open('lancamento', ['id' => 'formAno'])?>
                    <?= form_dropDown('ano', [2017, 2018, 2019, 2020], null, ['id' =>'ano', 'class' => 'form-control mb-2']) ?>
                    <input type="hidden" name="mes"> 
                <?= form_close() ?>
            </div>
        </div>
        <div class="row no-gutters d-flex justify-content-center mb-3">
            <?php 
                $mes = 1;
                $ano = 2020;
                $meses = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
                foreach ($meses as $mes_loop): ?>
                    <?php $classe = $mes == $mes_loop ? 'bg-warning' : '' ; ?>
                    <a href="<?=base_url("lancamento/index/{$mes_loop}/{$ano}")?>" class="nav-link <?=  $classe ?>"> 
                        <span class="text-uppercase small"> <?= nomeMes($mes_loop)?></span>
                    </a>            
            <?php endforeach ?>
        </div>
        <div class="table-responsive">
            <table class="table table-stripped table-hover">
                <thead>
                    <tr class="bg-dark text-white">
                        <th>Descrição</th>
                        <th>Data</th>
                        <th>Tipo</th>
                        <th>Consolidado?</th>
                        <th>Notificar</th>
                        <th>Valor</th>
                        <th class="text-center">Ação</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<?php echo $this->endSection('content')?>