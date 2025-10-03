<?php echo $this->extend('_common/layout') ?>
<?php echo $this->section('content') ?>


<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
        <li class="breadcrumb-item" aria-current="page"><?php echo anchor("perfil", 'Perfis') ?></li>
        <li class="breadcrumb-item active" aria-current="page">Edição de perfil</li>
    </ol>
</nav>


<h1>Perfis</h1>
<div class="card">
    <div class="card-header">
        Criação de perfil
    </div>
    <div class="card-body">
        <?php echo form_open('perfil/store') ?>
        <div class="form-group col-sm-6">
            <label for="descricao">Descrição</label>
            <input type="text" name="descricao" id="descricao" value="<?php echo isset($perfil['descricao']) ? $perfil['descricao'] : set_value('descricao') ?>" class="form-control" autofocus>
            <?php if (!empty($errors['descricao'])) : ?>
                <div class="alert alert-danger mt-2"><?php echo $errors['descricao'] ?></div>
            <?php endif; ?>
        </div>
        <div class="col-sm-6">
            <p>Control + Clique seleciona ou desseleciona uma regra.</p>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Página</th>
                        <th>Permissões</th>
                    </tr>
                    <?php if (isset($paginas)) : ?>
                        <?php foreach ($paginas as $pagina) : ?>
                            <?php
                            $selectName = !empty($chave) && !empty($pagina['id_permissao']) ?
                                "permissoes[{$pagina['paginas_id']}][id_permissao][{$pagina['id_permissao']}][]"
                                :
                                "permissoes[{$pagina['paginas_id']}][]";
                            $selectedOptionNenhuma = empty($pagina['regras']) || in_array('n', explode(',', $pagina['regras'])) ? 'selected' : ''
                            ?>
                            <tr>
                                <td><?php echo $pagina['nome_amigavel'] ?></td>
                                <td>
                                    <select name="<?php echo $selectName ?>" multiple class="form-control" style="min-height: 350px;">
                                        <option value="n" <?php echo $selectedOptionNenhuma ?>>Nenhuma</option>
                                        <?php foreach ($pagina['metodos'] as $metodo) : ?>
                                            <?php $selectedOption = in_array($metodo['nome_metodo'], explode(',', $pagina['regras'])) ? 'selected' : '' ?>
                                            
                                            <option <?php echo $selectedOption ?> value="<?php echo $metodo['nome_metodo'] ?>"><?php echo $metodo['nome_amigavel'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <input type="hidden" name="chave" value="<?php echo isset($perfil['chave']) ? $perfil['chave'] : set_value('chave'); ?>">
        <hr>
        <div class="form-group col-sm-12">
            <input type="submit" value="Salvar" class="btn btn-primary"?>
        </div>
        <?php echo form_close() ?>
    </div>
</div>

<?php echo $this->endSection('content') ?>