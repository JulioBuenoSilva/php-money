<?= $this->extend('_common/layout')?>
<?= $this->section('content')?>
<div class="alert alert-danger">
    <h4 class="alert-heading">Erro</h4>
    <hr>
    <p class="mb-0"><?php echo isset($mensagem) ? $mensagem : 'Erro desconhecido' ?></p>
</div>
<p><?= $link?></p>
<?= $this->endSection('content')?>