<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
        	'location' => [
        		'type' => 'VARCHAR',
        		'constraint' => 150
        	],
        	'role' => [
        		'type' => 'ENUM',
        		'constraint' => ['user', 'admin'],
        		'default' => 'user'
        	]
        ]);
    }

    public function down()
    {
        //
    }
}
