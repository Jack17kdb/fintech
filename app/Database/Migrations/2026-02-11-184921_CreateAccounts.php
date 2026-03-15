<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccounts extends Migration
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
            'account_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'blocked'],
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('accounts');
    }

    public function down()
    {
        $this->forge->dropTable('accounts');
    }
}
