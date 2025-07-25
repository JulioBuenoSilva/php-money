<?php echo $this->extend('_common/layout');
echo $this->section('content');?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <?= anchor('','Home')?></li>
        <li class="breadcrumb-item active" aria-current="pag"> Orçamentos</li>
    </ol>
</nav>
<div class="card">
    <div class="card-header">
        Orçamentos
    </div>
    <div class="card-body">
        Conteúdo
    </div>
</div>
<?php echo $this->endSection('content')?>