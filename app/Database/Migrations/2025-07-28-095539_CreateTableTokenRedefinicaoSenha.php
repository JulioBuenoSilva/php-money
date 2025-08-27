<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTokenRedefinicaoSenha extends Migration
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
				'constraint' 	=> 11
			],
			'token' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> 255
			],
			'ativo' => [
				'type' 			=> 'BOOLEAN',
				'default' 		=> true
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
			->createTable('token_redefinicao_senha');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('token_redefinicao_senha');
	}
}
