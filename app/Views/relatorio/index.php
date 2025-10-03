<?php echo $this->extend('_common/layout');
echo $this->section('content');?>

<link rel="stylesheet" href="<?= base_url('assets/datepicker/css/datepicker.css') ?>">

<script type="text/javascript" src="<?= base_url('assets/jquery.mask/jquery.mask.js') ?>"></script>

<script type="text/javascript" src="<?= base_url('assets/datepicker/js/bootstrap-datepicker.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/datepicker/js/locales/bootstrap-datepicker.pt-BR.js') ?>" charset="UTF-8"></script>

<script>
    // datepicker
    $(function() {
        $('#dataInicial, #dataFinal').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        autoclose: true,
        todayHighlight: true
        });
    });

    $(document).ready(function() {
        $(".reset").click(function(){ 
            const form = $(this).closest("form");

            form.find("input[type=text], textarea").val('');
            form.find("select").prop('selectedIndex', 0);
            form.find("input[type=radio], input[type=checkbox]").prop('checked', false); 
        });
    });
</script>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <?= anchor('','Home')?></li>
        <li class="breadcrumb-item active" aria-current="pag"> Relatorio</li>
    </ol>
</nav>
<h1>Relatório</h1>
<div class="card">
    <div class="card-header">
        Relatorio
    </div>
    <div class="card-body">
        <?= form_open('relatorio/getDados', [
            'autocomplete' => 'off', 
            'method' => 'GET'
        ])?>
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <div class="form-row mt-3">
                    <div class="col">
                        <label for="descricao">Descricao</label>
                        <input type="text" name="descricao" id="descricao" class="form-control" value="<?= $descricao ?? set_value('descricao') ?>">
                    </div>
                    <div class="col">
                        <label for="categorias_id">Categoria</label>
                        <?= form_dropdown('categorias_id', $dropDownCategorias, $categorias_id ?? set_value('categorias_id'), ['class' => 'form-control','id' => 'categorias_id']) ?>
                        <?php if (!empty($errors['categorias_id'])): ?>
                            <div class="alert alert-danger mt-2"><?= $errors['categorias_id'] ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col">
                        <label for="tipo">Tipo</label>
                        <?= form_dropdown('tipo', ['' => 'Tudo','d' => 'Despesa','r' => 'Receita'], set_value('tipo'), ['id' => 'tipo','class' => 'form-control']) ?>
                    </div>
                </div>

                <div class="form-row mt-3">
                    <div class="col">
                        <label for="dataInicial">Data Inicial</label>
                        <input type="text" name="dataInicial" id="dataInicial" class="form-control" value="<?= !empty($dataInicial) ? $dataInicial : '' ?>">
                    </div>
                    <div class="col">
                        <label for="dataFinal">Data Final</label>
                        <input type="text" name="dataFinal" id="dataFinal" class="form-control" value="<?= !empty($dataFinal) ? $dataFinal : '' ?>">
                    </div>
                    <div class="col">
                        <label for="consolidado">Consolidados?</label>
                        <?= form_dropdown('consolidado', ['' => 'Todos',1 => 'Sim',2 => 'Não'], !empty($consolidado) ? $consolidado : '', ['id' => 'consolidado','class' => 'form-control']) ?>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <div class="custom-control custom-radio">
                        <input type="radio" id="pdf" name="tipo_impressao" class="custom-control-input" value="pdf">
                        <label class="custom-control-label text-default" for="pdf">Gerar PDF</label>
                    </div>
                    <div class="custom-control custom-radio ">
                        <input type="radio" id="csv" name="tipo_impressao" class="custom-control-input" value="csv">
                        <label class="custom-control-label text-default" for="csv">Gerar CSV</label>
                    </div>
                </div>
                <div class="form-row mt-3 d-flex justify-content-end">
                    <input type="button" value="Limpar Filtros" class="btn btn-secondary btn-sm btn-clear reset">
                </div>
                <div class="form-row mt-3">
                    <div class="col text-center">
                        <button type="submit" class="btn btn-primary">Pesquisar</button>
                    </div>
                </div>
            </div>
        </div>
        <?= form_close() ?>
        <hr>
        <?php if(isset($categorias)) : ?>
            <?php if ($countLancamentos > 0) : ?>
                <p>Lançamentos retornados: <?= $countLancamentos ?></p>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="bg-dark text-white">
                                <th>Descricao</th>
                                <th>Data</th>
                                <th>Tipo</th>
                                <th>Consolidado?</th>
                                <th>Notificar?</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categorias as $categoria): ?>
                                <tr class="bg-light">
                                    <td colspan="6" class="justify-content-start"><strong> <?= $categoria['descricao'] ?> </strong></td>
                                </tr>
                                <?php foreach ($categoria['lancamentos'] as $lancamento) : ?>
                                    <?php $classeLancamento = $lancamento['tipo'] === 'd' ? 'text-danger' : 'text-success'?>
                                    <tr>
                                        <td class="pl-5 <?= $classeLancamento?>"> <?= $lancamento['descricao']?></td>
                                        <td class="pl-5 <?= $classeLancamento?>"> <?= toDataBR($lancamento['data'])?></td>
                                        <td class="pl-5 <?= $classeLancamento?>"> <?= $lancamento['tipo_formatado']?></td>
                                        <td class="pl-5 <?= $classeLancamento?>"> <?= $lancamento['consolidado_formatado']?></td>
                                        <td class="pl-5 <?= $classeLancamento?>"> <?= $lancamento['notificar_formatado']?></td>
                                        <td class="pl-5 <?= $classeLancamento?>"> <?= number_format($lancamento['valor'], 2, ',', '.')?></td>
                                        
                                    </tr>
                                <?php endforeach ?>
                                <tr>
                                    <td colspan="5" class="justify-content-start">Subtotal: </td>
                                    <td colspan="2" class="text-uppercase font-weight-bold <?= $classeLancamento?>"><strong> R$ <?= number_format($categoria['totalPorCategoria'], 2, ',', '.') ?> </strong></td>
                                </tr>
                            <?php endforeach ?>
                            <tr class="bg-light">
                                <td colspan="6" class="justify-content-start"><strong> TOTAIS </strong></td>
                            </tr>
                            <?php if ($tipo !== 'd') : ?>
                                <tr class="justify-content-end">
                                    <td colspan="5" class="text-success text-right">Total Receitas: </td>
                                    <td colspan="2" class="text-success"><strong> R$ <?= number_format($totalReceitas, 2, ',', '.') ?></strong></td>
                                </tr>
                            <?php endif ?>
                            <?php if ($tipo !== 'r') : ?>
                                <tr class="justify-content-end"> 
                                    <td colspan="5" class="text-danger text-right">Total Despesas:</td>
                                    <td colspan="2" class="justify-content-end text-danger"> <strong>R$ <?= number_format($totalDespesas, 2, ',', '.') ?> </strong></td>
                                </tr> 
                            <?php endif ?>
                            <tr class="bg-light">
                                <?php if ($saldo > 0 ) : ?> 
                                    <td colspan="5" class="text-success text-right"><strong> Saldo: </strong></td>
                                    <td colspan="2" class="text-success font-weight-bold"> R$ <?= number_format($saldo, 2, ',', '.') ?></td>
                                <?php elseif ($saldo < 0) : ?>
                                    <td colspan="5" class="text-danger text-right"><strong> Saldo: </strong></td>
                                    <td colspan="2" class="text-danger font-weight-bold"> R$ <?= number_format($saldo, 2, ',', '.') ?></td>
                                <?php else : ?>
                                    <td colspan="5" class="text-right"><strong> Saldo: </strong></td>
                                    <td colspan="2" class="font-weight-bold"> R$ <?= number_format($saldo, 2, ',', '.') ?></td>
                                <?php endif ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="text-center">Nenhum registro encontrado.</div>    
            <?php endif ?>
        <?php else : ?>
            <div class="text-center">Utilize os campos acima para criar sua pesquisa.</div>
        <?php endif ?>
    </div>
</div>
<?php echo $this->endSection('content')?>