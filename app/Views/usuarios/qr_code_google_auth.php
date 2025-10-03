<?php echo $this->extend('_common/layout') ?>
<?php echo $this->section('content') ?>

<style>
    .loading {
        min-height: 200px;
        min-width: 200px;
        background: url(<?php echo base_url('assets/imagens/loading.gif') ?>) center no-repeat;
    }
</style>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
        <li class="breadcrumb-item" aria-current="page"><?php echo anchor("usuario", 'Usuários') ?></li>
        <li class="breadcrumb-item" aria-current="page"><?php echo anchor("usuario/{$chave}/edit", 'Edição de Usuário') ?></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $titulo; ?></li>
    </ol>
</nav>

<div class="row d-flex justify-content-center">
    <div class="card">
        <div class="card-header">
            Autentição em 2 fatores
        </div>
        <div class="card-body">
            <p>Escaneie o QRCode abaixo com o aplicativo Google Authenticator no seu Celular e informe o código gerado:</p>
            <div class="row d-flex justify-content-center mb-3 loading">
                <img src="<?php echo $qrCodeUrl ?>" alt="QRCode do usuário">
            </div>
            <div class="row d-felx justify-content-center mb-3">
                <?php echo form_open('usuario/storeGoogleAuth', ['autocomplete' => 'off', 'class' => 'form-inline']) ?>
                <input type="number" min="0" name="code" class="form-control mr-2" placeholder="Digite o código" autofocus>
                <input type="hidden" name="secret" value="<?php echo $secret ?>">
                <input type="hidden" name="chave" value="<?php echo $chave ?>">
                
                <input type="submit" value="OK" class="btn btn-primary">                    
                <a href="<?= base_url('usuario') ?>" class="btn bg-light border-primary text-primary ml-2">Cancelar</a>
                </form>
            </div>
            <div class="d-flex justify-content-center">
                <?php $mensagem = session()->getFlashdata('mensagem'); ?>
                <?php if (!empty($mensagem)) : ?>
                    <div class="alert alert-info">
                        <?php echo $mensagem; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>


<?php echo $this->endSection('content') ?>