
<style>
/* Container */
.container {
    width: 100%;
    margin: 0 auto;
    padding: 15px;
    font-family: Arial, sans-serif;
    font-size: 12px;
}

/* Table */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}
.table th,
.table td {
    border: 1px solid #dee2e6;
    padding: 0.5rem;
    vertical-align: top;
}
.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}

/* Backgrounds */
.bg-dark { background-color: #343a40; color: #fff; }
.bg-light { background-color: #f8f9fa; }

/* Text colors */
.text-white { color: #fff; }
.text-success { color: #28a745; }
.text-danger { color: #dc3545; }

/* Text alignment */
.text-right { text-align: right; }
.text-center { text-align: center; }

/* Typography */
.text-uppercase { text-transform: uppercase; }
.font-weight-bold { font-weight: bold; }

/* Padding */
.pl-5 { padding-left: 3rem; }

/* Justify-content mimic (approx) */
.justify-content-start { text-align: left; }
.justify-content-end { text-align: right; }
</style>

<div class="container">
            
            <h1 style="text-align: center;">Relatório <?= $tipo === 'r' ? 'de Receitas' : ($tipo === 'd' ? 'de Despesas' : 'Financeiro Completo') ?>
            </h1>
            <h2 style="text-align: center; font-family: Arial, sans-serif; margin-bottom: 20px;">
                <?php if (!empty($descricao)) : ?>
                    <br>
                    - <?= esc($descricao) ?>
                <?php endif; ?>

                <?php if (!empty($categorias_id) && isset($categorias) && is_array($categorias)) : ?>
                    <br>
                    <?php 
                        $nomesCategorias = array_column($categorias, 'descricao');
                        echo ' (Categoria: ' . implode(', ', $nomesCategorias) . ')';
                    ?>
                <?php endif; ?>

                <?php if (!empty($dataInicial) && !empty($dataFinal)) : ?>
                    <br>
                    (Período: <?= $dataInicial ?> até <?= $dataFinal ?>)
                <?php elseif (!empty($dataInicial)) : ?>
                    <br>
                    (A partir de <?= $dataInicial ?>)
                <?php elseif (!empty($dataFinal)) : ?>
                    <br>
                    (Até <?= $dataFinal ?>)
                <?php endif; ?>

                <?php if (!empty($consolidado)) : ?>
                    <br>
                    - <?= $consolidado ? 'Consolidado' : 'Não Consolidado' ?>
                <?php endif; ?>

            </h2>

            <?php if(isset($categorias)) : ?>
            <?php if ($countLancamentos > 0) : ?>
                <p>Lançamentos retornados: <?= $countLancamentos ?></p>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="bg-dark text-white">
                                <th>Descricao</th>
                                <th>Data</th>
                                <th>Tipo</th>
                                <th>Consolidado?</th>
                                <th>Notificar?</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categorias as $categoria): ?>
                                <tr class="bg-light">
                                    <td colspan="6" class="justify-content-start"><strong> <?= $categoria['descricao'] ?> </strong></td>
                                </tr>
                                <?php foreach ($categoria['lancamentos'] as $lancamento) : ?>
                                    <?php $classeLancamento = $lancamento['tipo'] === 'd' ? 'text-danger' : 'text-success'?>
                                    <tr>
                                        <td class="pl-5 <?= $classeLancamento?>"> <?= $lancamento['descricao']?></td>
                                        <td class="pl-5 <?= $classeLancamento?>"> <?= toDataBR($lancamento['data'])?></td>
                                        <td class="pl-5 <?= $classeLancamento?>"> <?= $lancamento['tipo_formatado']?></td>
                                        <td class="pl-5 <?= $classeLancamento?>"> <?= $lancamento['consolidado_formatado']?></td>
                                        <td class="pl-5 <?= $classeLancamento?>"> <?= $lancamento['notificar_formatado']?></td>
                                        <td class="pl-5 <?= $classeLancamento?>"> <?= number_format($lancamento['valor'], 2, ',', '.')?></td>
                                        
                                    </tr>
                                <?php endforeach ?>
                                <tr>
                                    <td colspan="5" class="justify-content-start">Subtotal: </td>
                                    <td colspan="1" class="text-uppercase font-weight-bold text-right <?= $classeLancamento?>"><strong> R$ <?= number_format($categoria['totalPorCategoria'], 2, ',', '.') ?> </strong></td>
                                </tr>
                            <?php endforeach ?>
                            <tr class="bg-light">
                                <td colspan="6" class="justify-content-start"><strong> TOTAIS </strong></td>
                            </tr>
                            <?php if ($tipo !== 'd') : ?>
                                <tr class="justify-content-end">
                                    <td colspan="5" class="text-success text-right">Total Receitas: </td>
                                    <td colspan="1" class="text-success"><strong> R$ <?= number_format($totalReceitas, 2, ',', '.') ?></strong></td>
                                </tr>
                            <?php endif ?>
                            <?php if ($tipo !== 'r') : ?>
                                <tr class="justify-content-end"> 
                                    <td colspan="5" class="text-danger text-right">Total Despesas:</td>
                                    <td colspan="1" class="justify-content-end text-danger"> <strong>R$ <?= number_format($totalDespesas, 2, ',', '.') ?> </strong></td>
                                </tr> 
                            <?php endif ?>
                            <tr class="bg-light">
                                <?php if ($saldo > 0 ) : ?> 
                                    <td colspan="5" class="text-success text-right"><strong> Saldo: </strong></td>
                                    <td colspan="1" class="text-success font-weight-bold text-right"> R$ <?= number_format($saldo, 2, ',', '.') ?></td>
                                <?php elseif ($saldo < 0) : ?>
                                    <td colspan="5" class="text-danger text-right"><strong> Saldo: </strong></td>
                                    <td colspan="1" class="text-danger font-weight-bold text-right"> R$ <?= number_format($saldo, 2, ',', '.') ?></td>
                                <?php else : ?>
                                    <td colspan="5" class="text-right"><strong> Saldo: </strong></td>
                                    <td colspan="1" class="font-weight-bold text-right"> R$ <?= number_format($saldo, 2, ',', '.') ?></td>
                                <?php endif ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="text-center">Nenhum registro encontrado.</div>    
            <?php endif ?>
        <?php else : ?>
            <div class="text-center">Utilize os campos acima para criar sua pesquisa.</div>
        <?php endif ?>
</div>