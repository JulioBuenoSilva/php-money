<?php
echo $this->extend('_common/layout');
echo $this->section('content');?>

<link rel="stylesheet" href="<?= base_url('assets/datepicker/css/datepicker.css') ?>">

<script type="text/javascript" src="<?= base_url('assets/jquery.mask/jquery.mask.js') ?>"></script>

<script type="text/javascript" src="<?= base_url('assets/datepicker/js/bootstrap-datepicker.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/datepicker/js/locales/bootstrap-datepicker.pt-BR.js') ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?= base_url('assets/js/novaCategoria.js') ?>"></script>


<?= $this->include('_common/components/modalNovaCategoria') ?>

<script> 
    $(function() {
        $('#data').datepicker({
            format: "dd/mm/yyyy",
            todayBtn: "linked",
            language: "pt-BR",
            autoclose: true,
            todayHighlight: true
        });
    });
</script>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <?= anchor('','Home')?></li>
        <li class="breadcrumb-item"> <?= anchor('categoria','Lançamentos')?></li>
        <li class="breadcrumb-item" aria-current="page"> <?= $titulo ?></li>
    </ol>
</nav>
<h1>Lancamentos</h1>

<div class='card'>
    <div class='card-header'>
        <h2> <?= $titulo ?> </h2>
    </div>
    <div class='card-body'>
        <?= form_open('lancamento/store');?>
        <?= csrf_field(); ?>
            <div class="form-group col-sm-4">
                <label for="categorias_id">Categoria</label>
                <?= form_dropdown('categorias_id', $dropDownCategorias, $lancamento['categorias_id'] ?? set_value('categorias_id'), ['class' => 'form-control', 'id' => 'categorias_id', 'onchange' => 'modalNovaCategoria(this.value);'])?>
                <?php if (!empty($errors['categorias_id'])): ?>
                    <div class="alert alert-danger mt-2"><?= $errors['categorias_id'] ?></div>
                <?php endif; ?>
            </div>

            <div class="col-sm-4">
                <label for="valor">Valor</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">R$</div>
                        <input type="text" name="valor" id="valor" value="<?= $lancamento['valor'] ?? set_value('valor')?>">
                                
                        <?php if(!empty($errors['valor'])) : ?>
                            <div class="alert alert-danger mt-2"><?=$errors['valor'] ?></div> 
                        <?php endif?>
                    </div>
                </div>
            </div>

            <div class="form-group col-sm-2">
                <label for="data">Data</label>
                <input type="text" name="data" id="data" class="form-control" 
                    value="<?= !empty($lancamento['data']) 
                                ? toDataBR($lancamento['data']) 
                                : (isset($dia, $mes, $ano) 
                                        ? "{$dia}/{$mes}/{$ano}" 
                                        : set_value('data', date('d/m/Y'))) ?>" 
                    required>
            </div>

            <div class="form-group col-sm-6">
                <label for="descricao">Descrição</label>
                <input type="text" name="descricao" id="descricao" value="<?= $lancamento['descricao'] ?? set_value('descricao')?>" autofocus class="form-control">
                <?php if(!empty($errors['descricao'])) : ?>
                    <div class="alert alert-danger mt-2"><?=$errors['descricao'] ?></div> 
                <?php endif?>
            </div>

            <div class="form-group col-sm-8">
                <label class="mb-0">Consolidado?</label>
                <p class="mb-2">
                    <small>
                        Indica se o lançamento entrará nos cálculos de saldo.<br />
                        Se o lançamento for de uma data futura, este valor será registrado automaticamente como "Não".
                    </small>
                </p>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sim_consolidado" name="consolidado" class="custom-control-input" value="1" <?php echo !empty($lancamento['consolidado']) && (int)$lancamento['consolidado'] === 1 ? 'checked' : set_radio('consolidado', 1, true) ?> />
                    <label class="custom-control-label text-default" for="sim_consolidado">Sim</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="nao_consolidado" name="consolidado" class="custom-control-input" value="2" <?php echo !empty($lancamento['consolidado']) && (int)$lancamento['consolidado'] === 2 ? 'checked' : set_radio('consolidado', 2) ?> />
                    <label class="custom-control-label text-default" for="nao_consolidado">Não</label>
                </div>
            </div>

            <div class="form-group col-md-7">
                <label class="mb-0">Notificar por e-mail?</label>
                <p class="mb-2"><small>Marque se desejar receber um e-mail de lembrete no dia do vencimento deste lançamento.</small></p>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sim_notificar" name="notificar_por_email" class="custom-control-input" value="1" <?php echo !empty($lancamento['notificar_por_email']) && (int)$lancamento['notificar_por_email'] === 1 ? 'checked' : set_radio('notificar_por_email', 1) ?>>
                    <label class="custom-control-label text-default" for="sim_notificar">Sim</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="nao_notificar" name="notificar_por_email" class="custom-control-input" value="2" <?php echo !empty($lancamento['notificar_por_email']) && (int)$lancamento['notificar_por_email'] === 2 ? 'checked' : set_radio('notificar_por_email', 2, isset($lancamento['chave']) ? false : true) ?>>
                    <label class="custom-control-label text-default" for="nao_notificar">Não</label>
                </div>
            </div>
            <div class="form-group col-md-12">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="<?= base_url('orcamento') ?>" class="btn bg-light border-primary text-primary ml-2">Cancelar</a>
            </div>
        <?= form_close() ?>
</div>
<?php echo $this->endSection('content')?>