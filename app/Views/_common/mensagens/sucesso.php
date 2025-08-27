<?= $this->extend('_common/layout')?>
<?= $this->section('content')?>
<div class="alert alert-success">
    <h4 class="alert-heading">Sucesso</h4>
    <hr>
    <p class="mb-0"><?php echo isset($mensagem) ? $mensagem : 'Operação realizada com sucesso' ?></p>
</div>
<p><?= $link?></p>
<?= $this->endSection('content')?> 