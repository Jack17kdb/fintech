<?php

namespace App\Services;

use App\Models\AccountModel;
use App\Models\LedgerModel;

class AccountService{
	protected $accountModel;

	public function __construct(){
		$this->accountModel = new AccountModel();
	}

	public function createWallet($userId){
		return $this->accountModel->insert([
			"user_id" => $userId,
			'account_number' => $this->generateAccountNumber(),
            'status' => 'active'
		]);
	}

	private function generateAccountNumber(){
		return 'WAL' . time() . rand(100, 999);
	}

	public function getBalance($account_id){
		$ledgerModel = new LedgerModel();

		$credits = $ledgerModel
			->where('account_id', $account_id)
			->where('entry_type', 'credit')
			->selectSum('amount')
			->first()['amount'] ?? 0;

		$debits = $ledgerModel
			->where('account_id', $account_id)
			->where('entry_type', 'debit')
			->selectSum('amount')
			->first()['amount'] ?? 0;

		return $credits - $debits;
	}
}
