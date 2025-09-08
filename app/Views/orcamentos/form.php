<?php
echo $this->extend('_common/layout');
echo $this->section('content');
?>

<script type="text/javascript" src="<?= base_url('assets/js/novaCategoria.js')?>"></script>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <?= anchor('','Home')?></li>
        <li class="breadcrumb-item"> <?= anchor('orcamento','Orçamentos')?></li>
        <li class="breadcrumb-item active" aria-current="pag"> Orçamentos</li>
    </ol>
</nav>

<h1>Orçamentos</h1>

<div class="card">
    <div class="card-header"> <h2><?= $titulo?></h2></div>
    <div class="card-body">
    <?= form_open('orcamento/store')?>

        <div class="form-group col-sm-6">
            <label for="descricao">Descrição</label>
            <input type="text" name="descricao" id="descricao" value="<?= $orcamento['descricao'] ?? set_value('descricao')?>" autofocus class="form-control">
            <?php if(!empty($errors['descricao'])) : ?>
                <div class="alert alert-danger mt-2"><?=$errors['descricao'] ?></div> 
            <?php endif?>

        </div>

        <div class="form-group col-sm-6">
            <label for="categorias_id">
                Categorias
                <span id="spinnerLoading" style="display: none;" class="spinner-border spinner-border-sm ml-2" style="display: none;"></span>
            </label>

            <?= form_dropdown('categorias_id', $formDropDown, $categorias['id'] ?? set_value('id'), 
            [
                'class' => 'form-control', 
                'id' => 'categorias_id',
                'onchange' => 'modalNovaCategoria(this.value)'
            ])?>
        </div>

        <div class="col-sm-3">
            <label for="valor">Valor</label>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">R$</div>
                    <input type="text" name="valor" id="valor" value="<?= $orcamento['valor'] ?? set_value('valor')?>">
                            
                    <?php if(!empty($errors['valor'])) : ?>
                        <div class="alert alert-danger mt-2"><?=$errors['valor'] ?></div> 
                    <?php endif?>
                </div>
            </div>
        </div>

        <div class="form-group col-md-12">
            <label for="notificar">Notificar por e-mail?
                <div>
                    <small>Marque se deseja receber um e-mail quando os lançamentos atingirem 80% deste valor</small>
                    </div>
            </label>
            <div class="row col-sm-2">
                <?= form_dropdown('notificar_por_email', [2 => 'Não', 1 => 'Sim'], !empty($orcamento['notificar_por_email']) ? $orcamento['notificar_por_email'] : set_value('notificar_por_email'), ['id' => 'form-control', 'class' => 'form-control'])?>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <input type="submit" class="btn btn-primary border-primary text-light" value="Salvar">
            <a href="<?= base_url('orcamento') ?>" class="btn bg-light border-primary text-primary ml-2">Cancelar</a>
        </div>
        
        <input type="hidden" name="chave" value="<?= !empty($orcamento['chave']) ? $orcamento['chave'] : set_value('chave')?>">
    <?= form_close()?>
    </div>
</div>

<?= $this->include('_common/components/modalNovaCategoria') ?>
<?php echo $this->endSection('content')?>