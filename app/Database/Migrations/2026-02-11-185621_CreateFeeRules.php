<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFeeRules extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'transaction_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'min_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2'
            ],
            'max_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2'
            ],
            'fixed_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2'
            ],
            'percentage_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2'
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('fee_rules');
    }

    public function down()
    {
        $this->forge->dropTable('fee_rules');
    }
}
