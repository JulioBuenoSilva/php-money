<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableLancamentos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'descricao' => [
                'type'       => 'VARCHAR',
                'constraint' => 255
            ],
            'chave' => [
                'type'       => 'VARCHAR',
                'constraint' => 255
            ],
            'usuarios_id' => [
                'type'       => 'INT',
                'constraint' => 9
            ],
            'categorias_id' => [
                'type'       => 'INT',
                'constraint' => 9
            ],
            'valor' => [
                'type'       => 'DECIMAL',
                'constraint' => '8,2',
                'unsigned'   => true
            ],
            'data' => [
                'type' => 'DATE',
            ],
            'notificar_por_email' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 2,
                'comment' => '1 => SIM; 2 => NÃO'
            ],
            'consolidado' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 2,
                'comment' => '1 => SIM; 2 => NÃO'
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

        $this->forge->addKey('id', true); // Add primary key
        $this->forge->addKey('chave');
        $this->forge->addForeignKey('usuarios_id', 'usuarios', 'id', 'NO ACTION', 'CASCADE');
        $this->forge->addForeignKey('categorias_id', 'categorias', 'id', 'NO ACTION', 'CASCADE');
        $this->forge->createTable('lancamentos');
    }

    public function down()
    {
        $this->forge->dropTable('lancamentos');
    }
}
