<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PlatformAccountSeeder extends Seeder
{
    public function run()
    {
        $account_data = [
            'user_id' => 1,
            'account_number' => 'PLATFORM_REVENUE',
            'status' => 'active',
        ];

	$user_data = [
		'id' => 1,
		'name' => 'admin',
	        'email' => 'admin@gmail.com',
	        'password' => password_hash('adminpass', PASSWORD_DEFAULT),
        	'location' => 'Nairobi',
	        'status' => 'active',
		'role' => 'admin'
	];

	$this->db->table('users')->insert($user_data);
        $this->db->table('accounts')->insert($account_data);
    }
}
