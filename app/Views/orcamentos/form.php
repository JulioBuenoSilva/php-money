<?php
echo $this->extend('_common/layout');
echo $this->section('content');
?>

<script> 

    // chama o modalNovaCategoria
    function modalNovaCategoria(valor)
    {
        if (valor == 'n') {
            $('#modalNovaCategoria').modal('show');
            $('#modalNovaCategoria').on('shown.bs.modal', function(e) {
                $('#descricao_nova_categoria').focus();
                $('#descricao_nova_categoria').empty();
            });
        }
    }

    function salvaNovaCategoria()
    {
        var descricao = $('#descricao_nova_categoria');
        var tipo = $('#tipo_nova_categoria');

        if (descricao.val() == '' || tipo.val() == '') {
            alert('Preencha todos os campos antes de continuar');
            descricao.focus();
            return false;
        }

        $.post(base_url + 'Ajax/Categoria/store', {
            descricao: descricao.val(),
            tipo: tipo.val()
        }, function(data) {
            if (data.error === true ) {
                if ('descricao' in data.message) {
                    $('#erro_descricao').css({
                        'margin-top': '5px',
                        'color': 'red'
                    }).html(data.message['descricao'])
                } else {
                    $('#erro_descricao').html('').hide();
                } 
                if ('tipo' in data.message) {
                    $('#erro_tipo').css({
                        'margin-top': '5px',
                        'color': 'red'
                    }).html(data.message['tipo'])
                } else {
                    $('#erro_tipo').html('').hide();
                } 
            } else {
                $('erro_descricao').hide();
                $('#erro_tipo').hide();
                $('#modalNovaCategoria').modal('hide');
                carregaCategoriasDropdown(data.id);
            }
        }, 'json'
        );
    }

    function carregaCategoriasDropdown(id){
        $('#spinnerLoading').show();
        $selectCategorias = $('#categorias_id');
        $selectCategorias.empty();
        $.get(base_url + '/Ajax/Categoria/get', {}, 
            function(data) {
                data.forEach(categoria => {
                    if (id == categoria.id) {
                        $selectCategorias.append($('<option selected />').val(categoria.id).text(categoria.descricao));
                    }
                    else {
                        $selectCategorias.append($('<option />').val(categoria.id).text(categoria.descricao));
                    }
                });

                $optGroup = $("<optgroup label='---'>");
                $optGroup.append($('<option />').val('n').text('Nova Categoria...'));
                $selectCategorias.append($optGroup);
                $('#spinnerLoading').hide();
            }, 'json');
    }
</script>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <?= anchor('','Home')?></li>
        <li class="breadcrumb-item"> <?= anchor('orcamento','Orçamentos')?></li>
        <li class="breadcrumb-item active" aria-current="pag"> Orçamentos</li>
    </ol>
</nav>

<h1>Orçamentos</h1>

<div class="card">
    <div class="card-header"><?= $titulo?></div>
    <div class="card-body">
    <?= form_open('orcamento/store')?>

        <div class="form-group col-sm-6">
            <label for="descricao">Descrição</label>
            <input type="text" name="descricao" id="descricao" value="<?= $orcamento['descricao'] ?? set_value('descricao')?>" autofocus class="form-control">
            <?php if(!empty($errors['descricao'])) : ?>
                <div class="alert alert-danger mt-2"><?=$errors['descricao'] ?></div> 
            <?php endif?>

        </div>

        <div class="form-group col-sm-6">
            <label for="categorias_id">
                Categorias
                <span id="spinnerLoading" style="display: none;" class="spinner-border spinner-border-sm ml-2" style="display: none;"></span>
            </label>

            <?= form_dropdown('categorias_id', $formDropDown, $categorias['id'] ?? set_value('id'), 
            [
                'class' => 'form-control', 
                'id' => 'categorias_id',
                'onchange' => 'modalNovaCategoria(this.value)'
            ])?>
        </div>

        <div class="col-sm-3">
            <label for="valor">Valor</label>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">R$</div>
                    <input type="text" name="valor" id="valor" value="<?= $orcamento['valor'] ?? set_value('valor')?>">
                            
                    <?php if(!empty($errors['valor'])) : ?>
                        <div class="alert alert-danger mt-2"><?=$errors['valor'] ?></div> 
                    <?php endif?>
                </div>
            </div>
        </div>

        <div class="form-group col-md-12">
            <label for="notificar">Notificar por e-mail?
                <div>
                    <small>Marque se deseja receber um e-mail quando os lançamentos atingirem 80% deste valor</small>
                    </div>
            </label>
            <div class="row col-sm-2">
                <?= form_dropdown('notificar_por_email', [2 => 'Não', 1 => 'Sim'], !empty($orcamento['notificar_por_email']) ? $orcamento['notificar_por_email'] : set_value('notificar_por_email'), ['id' => 'form-control', 'class' => 'form-control'])?>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <input type="submit" class="btn btn-primary border-primary text-light" value="Salvar">
            <a href="<?= base_url('orcamento') ?>" class="btn bg-light border-primary text-primary ml-2">Cancelar</a>
        </div>
        
        <input type="hidden" name="chave" value="<?= !empty($orcamento['chave']) ? $orcamento['chave'] : set_value('chave')?>">
    <?= form_close()?>
    </div>
    <div class="modal fade" id="modalNovaCategoria" tabindex="-1" role="dialog" aria-labelledby="modalNovaCategoriaLabel"
    aria-hidden="true">
        <div class="modal-dialog" role="document"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nova Categoria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar"> 
                        <span aria-hidden="true"> &times; </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="descricao_nova_categoria">Categoria</label>
                        <input type="text" name="descricao_nova_categoria" id="descricao_nova_categoria" required class="form-control">
                        <div id="erro_descricao">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tipo_nova_categoria">Tipo</label>
                        <?= form_dropdown('tipo_nova_categoria', [
                            '' => 'Selecione', 
                            'd' => 'Despesa', 
                            'r' => 'Receita'
                        ], null, "id='tipo_nova_categoria' class='form-control'"
                        )?>
                        <div id="erro_tipo">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-light border-primary text-primary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="salvaNovaCategoria()">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->endSection('content')?>