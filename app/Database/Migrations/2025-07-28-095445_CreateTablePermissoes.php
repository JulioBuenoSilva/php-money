<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablePermissoes extends Migration
{
	public function up()
	{
		$this->forge->addField('id');
		$this->forge->addField([
			'regras' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> 200
			],
			'chave' => [
				'type' 			=> 'VARCHAR',
				'constraint'	=> 255
			],
			'perfis_id' => [
				'type' 			=> 'INT',
				'constraint' 	=> 11
			],
			'paginas_id' => [
				'type' 			=> 'INT',
				'constraint' 	=> 11
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
			->addForeignKey('paginas_id', 'paginas', 'id', 'NO ACTION', 'CASCADE')
			->addForeignKey('perfis_id', 'perfis', 'id', 'NO ACTION', 'CASCADE')
			->createTable('permissoes');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('permissoes');
	}
}

