<?php echo $this->extend('_common/layout') ?>
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
        <li class="breadcrumb-item"><?php echo anchor('', "Home") ?></li>
        <li class="breadcrumb-item active" aria-current="page">Usuários</li>
    </ol>
</nav>

<h1>Usuários</h1>
<div class="card">
    <div class="card-header">
        Usuários
    </div>
    <div class="card-body">
        <?php $mensagem = session()->getFlashdata('mensagem'); ?>
        <?php if (!empty($mensagem)) : ?>
            <div class="alert alert-info"> <?php echo $mensagem['mensagem']; ?>
            </div>
        <?php endif; ?>
        <div class="row col-md-2 mb-2">
            <?php echo anchor('usuario/create', 'Novo usuário', ['class' => "btn btn-primary"]) ?>
        </div>
        <p>Os usuários inseridos por você e por eles têm acesso a todos os registros inseridos por todos. Limite seus acessos através dos perfis.</p>
        <div class="table-responsive">
            <table class="table table-stripped table-hover">
                <thead>
                    <tr class="bg-dark text-white">
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Criado em</th>
                        <th>Perfil</th>
                        <th>Ativo</th>
                        <th class="text-center">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($meusDados) > 0) : ?>
                        <tr>
                            <td><?php echo $meusDados['nome'] ?></td>
                            <td><?php echo $meusDados['email'] ?></td>
                            <td><?php echo toDataBr($meusDados['created_at']) ?></td>
                            <td><?php echo !is_null($meusDados['descricao_perfil']) ? $meusDados['descricao_perfil'] : 'Administrador' ?> </td>
                            <td><?php echo $meusDados['ativo'] == 1 ? 'Sim' : 'Não' ?></td>
                            <td class="text-center">
                                <?php echo anchor("usuario/{$meusDados['chave_usuario']}/edit", 'Editar', ['class' => "btn btn-success btn-sm"]) ?>
                            </td>
                        </tr>
                        <?php if (count($meusUsuarios) > 0) : ?>
                            <tr>
                                <td colspan="7" class="bg-info text-light">Usuários cadastrados em seu perfil - (<?php echo count($meusUsuarios) ?>)</td>
                            </tr>
                            <?php foreach ($meusUsuarios as $meuUsuario) : ?>
                                <tr class="bg-light">
                                    <td class="pl-5"><?php echo $meuUsuario['nome'] ?> </td>
                                    <td><?php echo $meuUsuario['email'] ?> </td>
                                    <td><?php echo toDataBr($meuUsuario['created_at']) ?> </td>
                                    <td><?php echo $meuUsuario['descricao_perfil'] ?> </td>
                                    <td><?php echo intval($meuUsuario['ativo']) === 1 ? 'Sim' : 'Não' ?></td>
                                    <td class="text-center">
                                        <?php echo anchor("usuario/{$meuUsuario['chave_usuario']}/edit", 'Editar', ['class' => "btn btn-success btn-sm"]) ?>
                                        -
                                        <?php echo anchor("usuario/{$meuUsuario['chave_usuario']}/delete", 'Excluir', ['class' => "btn btn-danger btn-sm", 'onclick' => 'return valida()']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center">Você não cadastrou nenhum usuário no seu perfil ainda.</td>
                            </tr>
                        <?php endif; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center">Nenhum usuário cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>


<?php echo $this->endSection('content') ?>