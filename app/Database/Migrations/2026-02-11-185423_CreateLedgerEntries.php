<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLedgerEntries extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'transaction_id' => [
                'type' => 'INT'
            ],
            'account_id' => [
                'type' => 'INT'
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2'
            ],
            'entry_type' => [
                'type' => 'ENUM',
                'constraint' => ['debit', 'credit']
            ],
            'created_at' => [
        	'type' => 'DATETIME',
        	'null' => true,
	    ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('transaction_id', 'transactions', 'id');
        $this->forge->addForeignKey('account_id', 'accounts', 'id');

        $this->forge->createTable('ledger_entries');
    }

    public function down()
    {
        $this->forge->dropTable('ledger_entries');
    }
}
