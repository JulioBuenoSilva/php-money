<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMetodos extends Migration
{
	public function up()
	{
		$this->forge->addField('id');
		$this->forge->addField([
			'nome_amigavel' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> 255
			],
			'nome_metodo' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> 100
			],
			'chave' => [
				'type' 			=> 'VARCHAR',
				'constraint'			=> 255
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
			->createTable('metodos');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('metodos');
	}
}

