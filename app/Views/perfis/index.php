<?php echo $this->extend('_common/layout') ?>
<?php echo $this->section('content') ?>


<script type="text/javascript">
    function valida() {
        if (!confirm('Confirma a exclusão deste Perfil?')) {
            return false;
        }

        return true;
    }
</script>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo anchor('', "Home") ?></li>
        <li class="breadcrumb-item active" aria-current="page">Perfis</li>
    </ol>
</nav>

<h1>Perfis</h1>
<div class="card">
    <div class="card-header">
        Perfis
    </div>
    <div class="card-body">
        <?php $mensagem = session()->getFlashdata('mensagem'); ?>
        <?php if (!empty($mensagem)) : ?>
            <div class="alert alert-info">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        <div class="row col-sm-2 mb-2">
            <?php echo anchor('perfil/create', 'Novo Perfil', ['class' => "btn btn-primary"]) ?>
        </div>
        <div class="table-responsive">
            <table class="table table-stripped table-hover">
                <thead>
                    <tr class="bg-dark text-white">
                        <th>Descrição</th>
                        <th class="text-center">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($perfis) && count($perfis) > 0) : ?>
                        <?php foreach ($perfis as $perfil) : ?>
                            <tr>
                                <td class="pl-5"><?php echo $perfil['descricao'] ?></td>
                                <td class="text-center">
                                    <?php echo anchor("perfil/{$perfil['chave']}/edit", 'Editar', ['class' => "btn btn-success btn-sm"]) ?>
                                    -
                                    <?php echo anchor("perfil/{$perfil['chave']}/delete", 'Excluir', ['class' => "btn btn-danger btn-sm"]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <td colspan="5" class="text-center">Nenhum perfil encontrado.</td>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php echo $this->endSection('content') ?>