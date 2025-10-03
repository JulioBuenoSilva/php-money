<?php echo $this->extend('admin/_common/layout') ?>
<?php echo $this->section('content') ?>
<main>
    <script>
        $(document).ready(function() {
            $('#tableUsuarios').DataTable();
        });
    </script>
    <div class="container-fluid">
        <h1>Área Administrativa</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?php echo base_url('admin/home') ?>">Home</a></li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <h2>Área administrativa do sistema PHP Money.</h2>
                <p>Utilize os links ao lado para acessar as funcionalidades.</p>
            </div>
        </div>
    </div>
</main>

<?php echo $this->endSection('content') ?>