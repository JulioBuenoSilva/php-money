<?php echo $this->extend('admin/_common/layout') ?>
<?php echo $this->section('content') ?>

<style>
    .multi-culumn {
        column-count: 2;
    }

    .foto {
        max-width: 250px;
        min-height: 250px;
        border-radius: 20%;

    }

    .progress {
        width: 100%;
    }

    .polaroid {
        background-color: white;
        box-shadow: 0 4px 6px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
</style>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
        <li class="breadcrumb-item" aria-current="page"><?php echo anchor("admin/usuario", 'Usuários') ?></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $titulo; ?></li>
    </ol>
</nav>

<h1>Usuários</h1>
<div class="card">
    <div class="card-header">
        <?php echo $titulo; ?>
    </div>
    <div class="card-body">
        <?php $mensagem = session()->getFlashdata('mensagem'); ?>
        <?php if (!empty($mensagem)) : ?>
            <div class="alert alert-info">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-sm">
                <div class="card mb-2">
                    <div class="card-header">
                        Foto
                    </div>
                    <div class="card-body">
                        <div class="row d-flex justify-content-center">
                            <?php if (!empty($usuario['foto'])) : ?>
                                <img src="<?php echo base_url("usuario/getFoto/{$usuario['chave']}") ?>" class="card-img-top foto mb-3 polaroid" id="fotoUsuario">
                            <?php else : ?>
                                <img src="<?php echo base_url("assets/imagens/image-placeholder.png") ?>" class="card-img-top foto mb-3 polaroid" id="fotoUsuario">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php echo form_open('admin/usuario/store', ['autocomplete' => 'off']) ?>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" class="form-control" autofocus value="<?php echo !empty($usuario['email']) ? $usuario['email'] : set_value('email') ?>">
                    <?php if (!empty($errors['email'])) : ?>
                        <div class="alert alert-danger mt-2"><?php echo $errors['email'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" value="<?php echo !empty($usuario['nome']) ? $usuario['nome'] : set_value('nome') ?>">
                    <?php if (!empty($errors['nome'])) : ?>
                        <div class="alert alert-danger mt-2"><?php echo $errors['nome'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="perfis_id">Perfil</label>
                    <?php echo form_dropdown('perfis_id', $perfisDropDown, !empty($usuario['perfis_id']) ? $usuario['perfis_id'] : set_value('perfis_id'), "id='perfil' class='form-control'") ?>
                    <?php if (!empty($errors['perfis_id'])) : ?>
                        <div class="alert alert-danger mt-2"><?php echo $errors['perfis_id'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="senha">Nova Senha</label>
                    <input type="password" name="senha" id="senha" class="form-control">
                    <?php if (!empty($errors['senha'])) : ?>
                        <div class="alert alert-danger mt-2"><?php echo $errors['senha'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="senha_confirm">Repita a Nova Senha</label>
                    <input type="password" name="senha_confirm" id="senha_confirm" class="form-control">
                    <?php if (!empty($errors['senha_confirm'])) : ?>
                        <div class="alert alert-danger mt-2"><?php echo $errors['senha_confirm'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="tipo">Ativo?</label>
                    <?php echo form_dropdown('ativo', [false => 'Não', true => 'Sim'], !empty($usuario['ativo']) ? $usuario['ativo'] : set_value('ativo'), "id='ativo' class='form-control'") ?>
                    <?php if (!empty($errors['ativo'])) : ?>
                        <div class="alert alert-danger mt-2"><?php echo $errors['ativo'] ?></div>
                    <?php endif; ?>
                </div>
                <hr>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="<?= base_url('admin/usuario') ?>" class="btn bg-light border-primary ml-2 text-primary">Cancelar</a>
                </div>
                <input type="hidden" name="chave" value="<?php echo isset($usuario['chave']) ? $usuario['chave'] : set_value('chave') ?>">
                <?php echo form_close() ?>
            </div>
            <div class="col-sm">
                <?php if (isset($usuariosFilhos) && count($usuariosFilhos) > 0) : ?>
                    <h5>Usuários cadastrados por: <strong><?php echo $usuario['nome'] ?></strong></h5>
                    <?php echo isset($usuariosFilhos) ? ul($usuariosFilhos) : '' ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection('content') ?>