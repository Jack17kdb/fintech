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
	    'location' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
		'default' => 'Nairobi'
            ],
            'fixed_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2'
            ],
            'percentage_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
		'default' => '0.00',
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
        $this->forge->createTable('fee_rules');
    }

    public function down()
    {
        $this->forge->dropTable('fee_rules', true);
    }
}
