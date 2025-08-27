<?php echo $this->extend('_common/layout');
echo $this->section('content');?>

<script>
    function confirma() {
        if (!confirm('Deseja mesmo excluir o orçamento?')) {
            return false;
        }
        return true
    }
</script>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <?= anchor('','Home')?></li>
        <li class="breadcrumb-item active" aria-current="pag"> Orçamentos</li>
    </ol>
</nav>
<div class="card">
    <div class="card-header">
        Orçamentos - <?= count($orcamentos) ?> Registros encontrados 
    </div>
    <div class="card-body">
        Conteúdo
        <div class="row no-gutters d-flex justify-content-center justify-content-sm-between">
            <div class="my-3">
                <?= anchor('orcamento/create', 'Novo Orcamento', ['class' => 'btn btn-primary'])?>
            </div>    
            
            <?= form_open('orcamento', ['class' => 'form-inline', 'method' => 'GET']) ?>
                <div class="form-group d-flex justify-content-center my-3">
                    <input type="search" name="search" autocomplete="off" placeholder="Busca..." class="form-control" value="<?= $search?>">
                    <input type="submit" value="OK" class="ml-2 btn btn-primary">
                </div>
            <?= form_close()?>
        </div>
        <div class="table-responsive">
            <table class="table table-stripped table-hover">
                <thead>
                    <tr class="bg-dark text-white">
                        <th>Descricao</th>
                        <th>Categoria</th>
                        <th>Notificar</th>
                        <th>Valor</th>
                        <th class="text-center">Acao</th>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($orcamentos) > 0 ) : ?>
                        <?php foreach($orcamentos as $orcamento) : ?> 
                            <tr>
                                <td><?= $orcamento['descricao_orcamento'] ?></td>
                                <td><?= $orcamento['descricao_categorias']?></td>
                                <td><?= (int) $orcamento['notificar_por_email'] === '2' ? 'Não' : 'Sim' ?></td>
                                <td>R$ <?= number_format($orcamento['valor'], 2, ',', '.') ?></td>    
                                                            <td class="text-center"> 
                                    <?= anchor("orcamento/{$orcamento['chave_orcamento']}/edit", 'Editar', [
                                            'class' => 'btn btn-success btn-sm'
                                        ])
                                    ?>
                                    -        
                                    <?= anchor("orcamento/{$orcamento['chave_orcamento']}/delete", 'Excluir', [
                                        'class' => 'btn btn-danger btn-sm',
                                        'onclick' => "return confirma()"
                                    ]);
                                    ?> 
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center"> Nenhuma orçamento encontrado.</td>
                        </tr>
                    <?php endif?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php echo $this->endSection('content')?>