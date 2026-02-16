<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddToTransactions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transactions', [
		'user_id' => [
			'type' => 'INT'
		]
	]);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', 'user_id');
    }
}
