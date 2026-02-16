<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PlatformAccountSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'user_id' => null,
            'account_number' => 'PLATFORM_REVENUE',
            'status' => 'active',
        ];

        $this->db->table('accounts')->insert($data);
    }
}
