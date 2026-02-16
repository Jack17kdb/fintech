<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FeeRuleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'transaction_type' => 'wallet_transfer',
                'min_amount' => 0.01,
                'max_amount' => 100.00,
                'percentage_fee' => 2.00,
                'fixed_fee' => 0.25
            ],
            [
                'transaction_type' => 'wallet_transfer',
                'min_amount' => 100.01,
                'max_amount' => 1000.00,
                'percentage_fee' => 1.50,
                'fixed_fee' => 0.50
            ],
            [
                'transaction_type' => 'wallet_transfer',
                'min_amount' => 1000.01,
                'max_amount' => 999999.99,
                'percentage_fee' => 1.00,
                'fixed_fee' => 1.00
            ],
            [
                'transaction_type' => 'wallet_deposit',
                'min_amount' => 0.01,
                'max_amount' => 999999.99,
                'percentage_fee' => 0.00,
                'fixed_fee' => 0.00
            ],
            [
                'transaction_type' => 'wallet_withdrawal',
                'min_amount' => 0.01,
                'max_amount' => 999999.99,
                'percentage_fee' => 0.50,
                'fixed_fee' => 0.25
            ]
        ];

        $this->db->table('fee_rules')->insertBatch($data);
    }
}
