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

function salvaNovaCategoria() {
    var descricao = $('#descricao_nova_categoria');
    var tipo = $('#tipo_nova_categoria');

    if (descricao.val() === '' || tipo.val() === '') {
        alert('Preencha todos os campos antes de continuar');
        descricao.focus();
        return false;
    }

    $.post(base_url + 'Ajax/Categoria/store', {
        descricao: descricao.val(),
        tipo: tipo.val()
    }, function(data) {
        $('#erro_descricao').html('').hide();
        $('#erro_tipo').html('').hide();

        if (data.error === true) {
            if ('descricao' in data.message) {
                $('#erro_descricao').css({
                    'margin-top': '5px',
                    'color': 'red'
                }).html(data.message['descricao']);
            }
            if ('tipo' in data.message) {
                $('#erro_tipo').css({
                    'margin-top': '5px',
                    'color': 'red'
                }).html(data.message['tipo']);
            }
        } else {
               $('#erro_descricao').hide();
               $('#erro_tipo').hide();
               $('#modalNovaCategoria').modal('hide');
               carregaCategoriasDropdown(data.id);
        }
    }, 'json');
}


    // carrega as categorias no dropdown
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