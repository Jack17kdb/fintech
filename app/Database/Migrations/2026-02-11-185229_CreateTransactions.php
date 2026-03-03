<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'reference' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2'
            ],
            'fee_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'completed', 'failed'],
                'default' => 'pending'
            ],
            'created_at' => [
	        'type' => 'DATETIME',
		'null' => true
	    ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
