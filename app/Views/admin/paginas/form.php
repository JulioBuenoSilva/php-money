<?php echo $this->extend('admin/_common/layout') ?>
<?php echo $this->section('content') ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('admin'); ?>">Home</a></li>
        <li class="breadcrumb-item"><?php echo anchor("admin/pagina", 'Paginas') ?></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $titulo; ?></li>
    </ol>
</nav>

<h1>Páginas</h1>
<div class="card">
    <div class="card-header">
        <?php echo $titulo ?>
    </div>
    <div class="card-body">
        <?php $mensagem = session()->getFlashdata('mensagem'); ?>
        <?php if (!empty($mensagem)) : ?>
            <div class="alert alert-info">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        <?php echo form_open('admin/pagina/store') ?>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="nome_amigavel">Nome amigável</label>
                <input type="text" name="nome_amigavel" id="nome_amigavel" value="<?= $pagina['nome_amigavel'] ?? set_value('nome_amigavel') ?>" class="form-control" autofocus>
                <?php if (!empty($errors['nome_amigavel'])) : ?>
                    <div class="alert alert-danger mt-2"><?= $errors['nome_amigavel'] ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="classe">Nome da Classe</label>
                <input type="text" name="nome_classe" id="nome_classe" value="<?= $pagina['nome_classe'] ?? set_value('nome_classe') ?>" class="form-control">
                <?php if (!empty($errors['nome_classe'])) : ?>
                    <div class="alert alert-danger mt-2"><?= $errors['nome_classe'] ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label class="font-weight-bold">Métodos</label>
                <?php if (!isset($pagina['chave'])) : ?>
                    <p>Clique em Salvar para que os métodos apareçam</p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <?php if (isset($metodos)) : ?>
                    <?php if (count($metodos) > 0) : ?>
                        <?php foreach ($metodos as $metodo) :?>
                            <div class="form-row">
                                <div class="col">
                                    <label for="method">Método</label>
                                    <input type="text" name="method" id="method" value="<?= $metodo['nome_metodo'] ?>" class="form-control" readonly>
                                </div>
                                <div class="col">
                                    <label for="nome_amigavel">Nome Amigável</label>
                                    <input type="text" name="metodos[<?= $metodo['nome_metodo'] ?>]" class="form-control" value="<?= $metodo['nome_amigavel'] ?? '' ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>A classe <strong><?php echo $pagina['nome_classe'] ?></strong> não foi encontrada.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <input type="submit" value="Salvar" class="btn btn-primary">
                <a href="<?= base_url('admin/pagina') ?>" class="btn bg-light border-primary ml-2 text-primary">Cancelar</a>
            </div>
        </div>

        <input type="hidden" name="chave" value="<?php echo isset($pagina['chave']) ? $pagina['chave'] : set_value('chave') ?>">
        <?php echo form_close() ?>
    </div>
</div>

<?php echo $this->endSection('content') ?>