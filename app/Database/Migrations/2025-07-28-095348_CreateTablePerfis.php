<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablePerfis extends Migration
{
	public function up()
	{
		$this->forge->addField('id');
		$this->forge->addField([
			'descricao' => [
				'type' 			=> 'VARCHAR',
				'constraint'	=> 255
			],
			'chave' => [
				'type' 			=> 'VARCHAR',
				'constraint'			=> 255
			],
			'usuarios_id' => [
				'type' 			=> 'INT',
				'constraint' 	=> 9
			],
			'created_at' => [
				'type' 			=> 'DATETIME',
				'null'			=> TRUE,
			],
			'updated_at' => [
				'type' 			=> 'DATETIME',
				'null'			=> TRUE,
			],
			'deleted_at' => [
				'type' 			=> 'DATETIME',
				'null'			=> TRUE,
			]
		]);
		$this->forge
			->addKey('chave')
			->addForeignKey('usuarios_id', 'usuarios', 'id', 'NO ACTION', 'CASCADE')
			->createTable('perfis');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('perfis');
	}
}
