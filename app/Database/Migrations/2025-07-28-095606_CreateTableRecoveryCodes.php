<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableRecoveryCodes extends Migration
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
			'codigo' => [
				'type' 			=> 'VARCHAR',
				'constraint'	=> 255
			],
			'usuarios_id' => [
				'type' 			=> 'INT',
				'constraint' 	=> 9
			],
			'usado'              => [
				'type'    => 'BOOLEAN',
				'default' => false,
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
			->addKey('id', true)   // primary key on 'id'
			->addKey('codigo')     // optional index on 'codigo'
			->addForeignKey('usuarios_id', 'usuarios', 'id', 'NO ACTION', 'CASCADE')
			->createTable('recovery_codes');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('recovery_codes');
	}
}