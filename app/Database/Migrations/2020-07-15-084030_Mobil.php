<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Mobil extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id'          => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'merk'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
			],
			'plat' => [
				'type'           => 'VARCHAR',
				'constraint'     => '20',
			],
			'gambar' => [
				'type'           => 'TEXT',
				'null'           => TRUE,
			],
			'created_at' => [
				'type'           => 'DATETIME',
				'null'           => TRUE,
			],
			'updated_at' => [
				'type'           => 'DATETIME',
				'null'           => TRUE,
			],
		]);
		$this->forge->addKey('id', TRUE);
		$this->forge->createTable('mobil');
	}

	public function down()
	{
		$this->forge->dropTable('mobil');
	}
}
