<?php echo $this->extend('admin/_common/layout') ?>
<?php echo $this->section('content') ?>

<script type="text/javascript">
    function valida() {
        if (!confirm('Ao excluir uma página, todos os métodos vinculados a ela também serão excluído.\nDeseja continuar?')) {
            return false;
        }

        return true;
    }
</script>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Páginas</li>
    </ol>
</nav>

<h1>Páginas</h1>
<div class="card">
    <div class="card-header">
        Páginas
    </div>
    <div class="card-body">
        <?php $mensagem = session()->getFlashdata('mensagem'); ?>
        <?php if (!empty($mensagem)) : ?>
            <div class="alert alert-info">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        <div class="row col-md-2 mb-2">
            <?php echo anchor('admin/pagina/create', 'Nova página', 'class="btn btn-primary"') ?>
        </div>
        <p>Atenção! A classe php e seus métodos já devem existir no sistema antes de você inserir uma nova página.</p>
        <div class="table-responsive">
            <table class="table table-stripped table-hover">
                <thead>
                    <tr class="bg-dark text-white">
                        <th>Nome amigável</th>
                        <th>Nome da classe</th>
                        <th class="text-center">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($paginas) > 0) : ?>

                        <?php foreach ($paginas as $pagina) : ?>
                            <tr>
                                <td class="pl-5"><?php echo $pagina['nome_amigavel'] ?></td>
                                <td class="pl-5"><?php echo $pagina['nome_classe'] ?></td>
                                <td class="text-center">
                                    <?php echo anchor("admin/pagina/{$pagina['chave']}/edit", 'Editar', 'class="btn btn-success btn-sm"') ?>
                                    -
                                    <?php echo anchor("admin/pagina/{$pagina['chave']}/delete", 'Excluir', 'class="btn btn-danger btn-sm" onclick="return valida()"') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <td colspan="5" class="text-center">Nenhum página encontrada.</td>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php echo $this->endSection('content') ?>