<?php echo $this->extend('admin/_common/layout') ?>
<?php echo $this->section('content') ?>
<script type="text/javascript">
    function valida() {
        if (!confirm('Confirma a exclusão deste usuário?')) {
            return false;
        }
        return true;
    }
</script>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/home') ?>">Home</a></li>
        <li class="breadcrumb-item active">Usuários</li>
    </ol>
</nav>

<h1>Usuários Cadastrados no sistema</h1>
<div class="card">
    <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        <?php echo $totalUsuarios ?> Usuários cadastrados
    </div>
    <div class="card-body">
        <?php $mensagem = session()->getFlashdata('mensagem'); ?>
        <?php if (!empty($mensagem)) : ?>
            <div class="alert alert-info"> <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table" id="tableUsuarios">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Email</th>
                        <th>E-mail confirmado?</th>
                        <th>Data de Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario) : ?>
                        <tr>
                            <td><?php echo $usuario['nome'] ?></td>
                            <td><?php echo $usuario['email'] ?></td>
                            <td><?php echo $usuario['email_confirmado'] == 1 ? 'Sim' : 'Não' ?></td>
                            <td><?php echo toDataBR($usuario['created_at'], true) ?></td>
                            <td>
                                <?php echo anchor("admin/usuario/{$usuario['chave']}/edit", 'Editar', ['class' => 'btn btn-success btn-sm']) ?>
                                <?php echo anchor("admin/usuario/{$usuario['chave']}/delete", 'Excluir', ['class' => 'btn btn-danger btn-sm', 'onclick' => 'return valida()']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <?php echo $pager->links('default', 'bootstrap_pager') ?>
        </div>
    </div>
</div>

<?php echo $this->endSection('content') ?>