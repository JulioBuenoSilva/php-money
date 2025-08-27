<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablePaginas extends Migration
{
	public function up()
	{
		$this->forge->addField('id');
		$this->forge->addField([
			'nome_amigavel' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> 200
			],
			'chave' => [
				'type' 			=> 'VARCHAR',
				'constraint'			=> 255
			],
			'nome_classe' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> 100
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
			->createTable('paginas');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('paginas');
	}
}

