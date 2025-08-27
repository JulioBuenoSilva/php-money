<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'chave' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'perfis_id' => [
                'type' => 'INT',
                'constraint' => 9,
                'null' => true,
            ],
            'usuario_pai' => [
                'type' => 'INT',
                'constraint' => 9,
                'null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'email_confirmado' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'senha' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'token_confirmacao_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'token_criado_em' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'ativo' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'secret_google_auth' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
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
        $this->forge->addKey('chave');
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
