<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\AccountModel;
use App\Services\AccountService;


class WalletController extends BaseController{
	protected $transactionModel;
	protected $accountService;
	protected $accountModel;

	public function __construct(){
		$this->transactionModel = new TransactionModel();
		$this->accountModel = new AccountModel();
		$this->accountService = new AccountService();
	}

	public function dashboard(){
		$wallet = $this->accountModel->where('user_id', session()->get('user_id'))->first();

		$wallet['balance'] = $this->accountService->getBalance($wallet['id']);

		return view('wallet/dashboard', [
            'wallet' => $wallet
        ]);
	}

	public function transactions(){
		$transactions = $this->transactionModel
            ->where('user_id', session()->get('user_id'))
            ->orderBy('created_at', 'DESC')
            ->paginate(5);

        return view('wallet/transactions', [
            'transactions' => $transactions,
            'pager' => $this->transactionModel->pager
        ]);
	}
}