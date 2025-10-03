<?php echo $this->extend('_common/layout') ?>
<?php echo $this->section('content') ?>
<script src="<?php echo base_url('assets/jquery.form/jquery.form.min.js') ?>"></script>
<script>
    function confirmaDesativacao() {
        if (!confirm("Confirma a desativação da autenticação em 2 fatores?")) {
            return false;
        }

        return true;
    }

    function confirmaRecoveryCodes() {
        if (!confirm("Se você já possuir códigos gerados, ele serão substituídos por novos. Deseja continuar?")) {
            return false;
        }

        return true;
    }


    $(function() {

        var bar = $('.progress-bar');
        var percent = $('.percent');

        $('#btn-upload').click(function(e) {
            e.preventDefault();
            $('#inputFoto').click();
        });

        $('#inputFoto').change(function(e) {
            e.preventDefault();
            $('#fotoForm').submit();

        });

        $('#fotoForm').ajaxForm({
            dataType: 'json',
            beforeSend: function() {
                $('#status').hide();
                var percentVal = '0%'
                bar.width(percentVal);
                percent.html(percentVal)
                $('.progress').show();
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                bar.width(percentVal);
                percent.html(percentVal);

            },
            success: function(responseText, statusText, xhr, $form) {
                var percentVal = '100%';
                bar.width(percentVal);
                percent.html(percentVal);
                if (responseText.error == true) {
                    $('#status').text(responseText.message);
                    $('#status').show();
                }

                if (responseText.error == false) {
                    $('#fotoUsuario').attr('src', responseText.path);
                } else {
                    percent.hide();
                }
            },
            complete: function(xhr) {
                $('.progress').hide();

            }
        });

    });
</script>



<style>
    .multi-column {
        column-count: 2;
    }

    .foto {
        max-width: 250px;
        min-height: 250px;
        ;
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
        <li class="breadcrumb-item" aria-current="page"><?php echo anchor("usuario", 'Usuários') ?></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $titulo; ?></li>
    </ol>
</nav>

<h1>Usuários</h1>
<div class="card">
    <div class="card-header">
        <?php echo $titulo ?>
        <?php if (isset($chave) && $chave == session()->chave) : ?>
            - <span class="text-danger"><small>Não é permitido alterar o próprio e-mail ou perfil.</small></span>
        <?php endif; ?>
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
                    <div class="card-header">Foto</div>
                    <div class="card-body">
                        <div class="row d-flex justify-content-center">
                            <?php if (!empty($usuario['foto'])) : ?>
                                <img src="<?php echo base_url("usuario/getFoto/{$usuario['chave']}") ?>" alt="Foto de perfil do usúario." class="card-img-top foto mb-3 polaroid" id="fotoUsuario">
                            <?php else : ?>
                                <img src="<?php echo base_url("assets/imagens/image-placeholder.png") ?>" alt="Foto de perfil do usúario." class="card-img-top foto mb-3 polaroid" id="fotoUsuario">
                            <?php endif; ?>

                        </div>
                        <p><small>Enviar foto em proporção 1:1</small></p>
                        <?php echo form_open_multipart('ajax/usuario/storeFoto', ['id' => 'fotoForm', 'class' => 'form-inline']) ?>
                        <input type="file" name="foto" class="form-control" id="inputFoto" style="display: none">
                        <?php if (!empty($chave)) : ?>
                            <button class="btn btn-secondary btn-sm" id="btn-upload">Alterar Foto</button>
                        <?php else : ?>
                            <p>Salve o registro antes de inserir a foto.</p>
                        <?php endif; ?>
                        <input type="hidden" name="chave" value="<?php echo isset($usuario['chave']) ? $usuario['chave'] : set_value('chave') ?>">
                        <?php echo form_close(); ?>
                        <div class="progress mt-3" style="display: none">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                <span class="percent">0%</span>
                            </div>
                        </div>
                        <div class="text-danger mt-3" id="status" style="display: none;">Erro</div>
                    </div>
                </div>
                <?php echo form_open('usuario/store', ['autocomplete' => 'off']) ?>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" class="form-control" autofocus value="<?php echo !empty($usuario['email']) ? $usuario['email'] : set_value('email') ?>" <?php echo isset($chave) && $chave == session()->chave ? 'readonly' : '' ?>>
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
                <?php if (isset($chave) && $chave == session()->chave) :?>
                    <div class="form-group">
                        <label>Perfil</label>
                        <input type="text" value="<?php echo !empty($nomePerfil) ? $nomePerfil : 'Administrador' ?>" class="form-control" disabled>
                        <input type="hidden" name="perfis_id" value="<?php echo !empty($usuario['perfis_id']) ? $usuario['perfis_id'] : set_value('perfis_id') ?>">
                    </div>
                <?php else : ?>
                    <div class="form-group">
                        <label for="perfis_id">Perfil</label>
                        <?php echo form_dropdown('perfis_id', $perfisDropDown, !empty($usuario['perfis_id']) ? $usuario['perfis_id'] : set_value('perfis_id'), ['id' => 'perfil', 'class' => 'form-control']) ?>
                        <?php if (!empty($errors['perfis_id'])) : ?>
                            <div class="alert alert-danger mt-2"><?php echo $errors['perfis_id'] ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <hr>
                <?php if (isset($chave) && $chave == session()->chave) : ?>
                    <div class="form-group">
                        <label for="senha_atual">Senha Atual</label>
                        <input type="password" name="senha_atual" id="senha_atual" class="form-control">
                        <?php if (!empty($errors['senha_atual'])) : ?>
                            <div class="alert alert-danger mt-2"><?php echo $errors['senha_atual'] ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
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
                <?php if (isset($chave) && $chave == session()->chave) : ?>
                    <hr>
                    <div class="form-group">
                        <label>Autenticação em 2 fatores</label>
                        <?php if (isset($usuario) && is_null($usuario['secret_google_auth'])) : ?>
                            <div class="form-group">
                                <?php echo anchor("usuario/googleAuth", 'Habilitar Autenticação em 2 fatores...', ['class' => "btn btn-primary btn-sm"]) ?>
                            </div>
                        <?php else : ?>
                            <p><strong>Ativa</strong><?php echo anchor('usuario/desativaAuth2Fatores', 'Clique para Desativar', ['class' => "nav-link", 'onclick' => 'return confirmaDesativacao()']) ?></p>
                            <p><?php echo anchor('usuario/createBackupCodes', 'Gerar Códigos de Backup', ['class' => "nav-link", 'onclick' => 'return confirmaRecoveryCodes()']) ?></p>
                            <?php if (isset($recoveryCodes) && count($recoveryCodes) > 0) : ?>
                                <details>
                                    <summary>Ver códigos</summary>
                                    <ol class="multi-column">
                                        <?php foreach ($recoveryCodes as $code) : ?>
                                            <?php if ($code['usado'] == true) : ?>
                                                <li><del><?php echo $code['codigo'] ?></del></li>
                                            <?php else : ?>
                                                <li><?php echo $code['codigo'] ?></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ol>
                                </details>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <hr>
                <?php endif; ?>
                <div class="form-group">
                    <label for="tipo">Ativo?</label>
                    <?php echo form_dropdown('ativo', [false => 'Não', true => 'Sim'], isset($usuario['ativo']) ? (bool) $usuario['ativo'] : set_value('ativo', 1), ['id' => 'ativo', 'class' => 'form-control']) ?>
                    <?php if (!empty($errors['ativo'])) : ?>
                        <div class="alert alert-danger mt-2"><?php echo $errors['ativo'] ?></div>
                    <?php endif; ?>
                </div>
                <hr>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="<?= base_url('usuario') ?>" class="btn bg-light border-primary text-primary ml-2">Cancelar</a>
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