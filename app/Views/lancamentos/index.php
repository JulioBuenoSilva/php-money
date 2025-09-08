<?php echo $this->extend('_common/layout');
echo $this->section('content');?>

<script>
    function confirma() {
        if (!confirm('Deseja mesmo excluir a categoria ?')) {
            return false;
        }
        return true
    }

    $(function() {
        $('#ano').change(function(){
            $('#formAno').submit();
        })
    }); 
</script>

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
        <span class="text-success d-block d-sm-inline">Receitas Geral: R$ <?= number_format($receitas, 2, ',', '.') ?> </span>
        
        &nbsp; - &nbsp;
        
        <span class="text-danger d-block d-sm-inline">Despesas Geral: R$ <?= number_format($despesas, 2, ',', '.') ?></span>
        
        &nbsp; = &nbsp;  
        
        <?php if ($saldo > 0 ) : ?> 
            <span class="text-success d-block d-sm-inline">Saldo Geral: <strong> R$ <?= number_format($saldo, 2, ',', '.') ?> </strong></span>
        <?php elseif ($saldo < 0) : ?>
            <span class="text-danger d-block d-sm-inline">Saldo Geral: <strong> R$ <?= number_format($saldo, 2, ',', '.') ?> </strong></span>
        <?php else : ?>
            <span class="d-block d-sm-inline">Saldo Geral: <strong> R$ <?= number_format($saldo, 2, ',', '.') ?> </strong></span>
        <?php endif ?>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <div class="row justify-content-center justify-content-sm-start">
            <div class="text-center">
                Ultimos lançamentos - <?= $countLancamentos ?> - registros encontrados - <strong class="text-uppercase"> <?= date('m/Y') ?> </strong>
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
            <?= form_open('lancamento', ['class' => 'form-inline', 'method' => 'GET'])?>
                <?= csrf_field() ?>
                <div class="form-group d-flex justify-content-center justify-content-sm-between">
                    <input type="search" name="search" autocomplete="off" placeholder="Busca..." class="form-control" value="<?=  $search ?? '' ?>">
                    <input type="submit" value="OK" class="ml-2 btn btn-primary">
                </div>
            <?= form_close() ?>
        </div>
        <div class="row no-gutters d-flex justify-content-center">
            <div class="my-3">
                <?= form_open('lancamento', ['id' => 'formAno'])?>
                    <?= csrf_field() ?>
                    <?= form_dropDown('ano', [2017 => 2017, 2018 => 2018, 2019 => 2019, 2020 => 2020, 2025 => 2025], $ano, ['id' =>'ano', 'class' => 'form-control mb-2']) ?>
                    <input type="hidden" name="mes" value="<?= $mes ?>"> 
                <?= form_close() ?>
            </div>
        </div>
        <div class="row no-gutters d-flex justify-content-center mb-3">
            <?php 
                $meses = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
                foreach ($meses as $mes_loop): ?>
                    <?php $classe = $mes == $mes_loop ? 'bg-warning' : '' ; ?>
                    <a href="<?=base_url("lancamento/index/{$mes_loop}/{$ano}")?>" class="nav-link <?=  $classe ?>"> 
                        <span class="text-uppercase small"> <?= nomeMes($mes_loop)?></span>
                    </a>            
            <?php endforeach ?>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
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
                <tbody>
                    <?php if (count($categorias) > 0) : ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr class="bg-light">
                                <td colspan="7" class="justify-content-start"><strong> <?= $categoria['descricao'] ?> </strong></td>
                            </tr>
                            <?php foreach ($categoria['lancamentos'] as $lancamento): ?>
                                <?php $classe = $lancamento['tipo'] === 'd' ? 'text-danger' : 'text-success' ?>
                                <tr class="<?= $classe?>">
                                    <td class="pl-5"> <?= $lancamento['descricao'] ?> </td>
                                    <td> <?= toDataBr($lancamento['data']) ?> </td>
                                    <td> <?= $lancamento['tipo_formatado']?> </td>
                                    <td> <?= $lancamento['consolidado_formatado'] ?> </td>
                                    <td> <?= $lancamento['notificar_formatado'] ?> </td>
                                    <td> <?= number_format($lancamento['valor'], 2, ',', '.') ?> </td>
                                    <td class="text-center">
                                        <?= anchor("lancamento/{$lancamento['chave']}/edit", 'Editar', ['class' => 'btn btn-sm btn-success', 'title' => 'Editar lançamento']) ?>
                                        <?= anchor("lancamento/{$lancamento['chave']}/delete", 'Excluir', ['class' => 'btn btn-sm btn-danger', 'title' => 'Excluir lançamento', 'onclick' => "return confirma('Confirma a exclusão do lançamento?')"]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="5" class="text-right"><strong>Subtotal: </strong></td>
                                <td colspan="2" class="text-uppercase font-weight-bold"><strong> R$ <?= number_format($categoria['totalPorCategoria'], 2, ',', '.') ?> </strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>  
                            <tr>
                                <td colspan="7" class="text-center"> Nenhum lançamento encontrado </td>
                            </tr>
                    <?php endif ?>
                    <tr> 
                        <td colspan="7" class="bg-light font-weight-bold text-uppercase"> <strong>Totalizador</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Saldo do Mês Anterior:</td>
                        <td colspan="1"> <strong> R$ <?= number_format($saldoMesAnterior, 2, ',', '.')?> </strong> </td>
                    </tr>
                    <tr> 
                        <td colspan="3" class="text-success text-right">Receitas neste Mês</td>
                        <td colspan="3" class="text-success"> R$ <?= number_format($receitasMesAtual, 2, ',', '.') ?></td>
                    </tr>
                    <tr> 
                        <td colspan="3" class="text-danger text-right">Despesas neste Mês</td>
                        <td colspan="3" class="text-danger"> R$ <?= number_format($despesasMesAtual, 2, ',', '.') ?></td>
                    </tr>
                    <tr> 
                        <td colspan="3" class="text-right"><strong> Saldo neste Mês </strong></td>
                        <?php if ($saldoMesAtual > 0 ) : ?> 
                            <td colspan="3" class="text-success font-weight-bold"> R$ <?= number_format($saldoMesAtual, 2, ',', '.') ?></td>
                        <?php elseif ($saldoMesAtual < 0) : ?>
                            <td colspan="3" class="text-danger font-weight-bold"> R$ <?= number_format($saldoMesAtual, 2, ',', '.') ?></td>
                        <?php else : ?>
                            <td colspan="3"> R$ <?= number_format($saldoMesAtual, 2, ',', '.') ?></td>
                        <?php endif ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php echo $this->endSection('content')?>