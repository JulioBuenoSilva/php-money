<?php
echo $this->extend('_common/layout');
echo $this->section('content');?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <?= anchor('','Home')?></li>
        <li class="breadcrumb-item"> <?= anchor('categoria','Categorias')?></li>
        <li class="breadcrumb-item" aria-current="page"> <?= $titulo ?></li>
    </ol>
</nav>

<h1>Categorias</h1>
<div class="card">
    <div class="card-header"><h2><?= $titulo?></h2></div>
    <div class="card-body">
        <?= form_open('categoria/store') ?> 
            <div class="form-group col-sm-6">
                <label for="descricao">Descricao</label>
                <input type="text" name="descricao" id="descricao" class="form-control" autofocus value="<?= $categoria['descricao'] ?? set_value('descricao') ?? '' ?>">
                <?php if(!empty($errors['descricao'])) : ?>
                        <div class="alert alert-danger mt-2"><?=$errors['descricao'] ?></div> 
                <?php endif?>
            </div>
            <div class="form-group col-sm-4">
                <label for="tipo">Tipo</label>
                <?php 
                    $options = [
                        ''  => 'Selecione',
                        'd' => 'Despesa',
                        'r' => 'Receita'
                    ];

                    echo form_dropdown('tipo', $options, $categoria['tipo'] ?? set_value('tipo') ?? '', ['id' => 'tipo', 'class' => 'form-control', ]);
                        
                    if(!empty($errors['tipo'])) : ?>
                        <div class="alert alert-danger mt-2"><?=$errors['tipo'] ?></div> 
                <?php endif ?>      
            </div>
            <div class="form-group col-sm-12">
                <input type="submit" class="btn btn-primary border-primary" value="Salvar">
                <a href="<?= base_url('orcamento') ?>" class="btn bg-light border-primary ml-2 text-primary">Cancelar</a>
            </div>
            <input type="hidden" name="chave" value="<?= set_value('chave', $categoria['chave'] ?? '') ?>">
        <?= form_close() ?>
    </div>
</div>

<?php echo $this->endSection('content')?>