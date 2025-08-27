<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableOrcamentos extends Migration
{
	public function up()
	{
		$this->forge->addField('id');
		$this->forge->addField([
			'chave' => [
				'type' 			=> 'VARCHAR',
				'constraint'			=> 255
			],
			'usuarios_id' => [
				'type' 			=> 'INT',
				'constraint' 	=> 9
			],
			'categorias_id' => [
				'type' 			=> 'INT',
				'constraint' 	=> 9
			],
			'descricao' => [
				'type' 			=> 'VARCHAR',
				'constraint'	=> 255
			],
			'valor' => [
				'type' 			=> 'DECIMAL(8,2)',
				'unsigned'		=> TRUE
			],
			'notificar_por_email' => [
				'type' 			=> 'BOOLEAN',
				'default'		=> TRUE
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
			->addForeignKey('categorias_id', 'categorias', 'id', 'NO ACTION', 'CASCADE')
			->addForeignKey('usuarios_id', 'usuarios', 'id', 'NO ACTION', 'CASCADE')
			->createTable('orcamentos');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('orcamentos');
	}
}

