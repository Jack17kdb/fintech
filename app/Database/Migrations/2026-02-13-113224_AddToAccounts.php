<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddToAccounts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('accounts', [
		'balance' => [
			'type' => 'INT',
			'unsigned' => true,
			'balance' => 0
		],
	]);
    }

    public function down()
    {
        $this->forge->dropColumn('accounts', 'balance');
    }
}
