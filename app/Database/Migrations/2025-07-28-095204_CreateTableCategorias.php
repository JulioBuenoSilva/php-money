<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableCategorias extends Migration
{
    public function up()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'descricao' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'chave' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'usuarios_id' => [
                'type' => 'INT',
                'constraint' => 9
            ],
            'tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 1
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge
        ->addKey('chave')
        ->addForeignKey('usuarios_id', 'usuarios', 'id', 'NO ACTION', 'CASCADE')
        ->createTable('categorias');
    }

    public function down()
    {
        $this->forge->dropTable('categorias');  
    }
}
