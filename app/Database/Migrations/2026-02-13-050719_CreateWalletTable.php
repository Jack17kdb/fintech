<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWalletTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'INT'
            ],
            'balance' => [
                'type' => 'INT'
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'active'
            ],
            'created_at' => [
                'type' => 'DATETIME',
		'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
		'null' => true
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('user_id');
        $this->forge->createTable('wallet');
    }

    public function down()
    {
        $this->forge->dropTable('wallet');
    }
}
