<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\AccountModel;
use App\Models\LedgerModel;
use App\Services\AccountService;


class WalletController extends BaseController{
	protected $transactionModel;
	protected $accountService;
	protected $accountModel;
	protected $ledgerModel;

	public function __construct(){
		$this->transactionModel = new TransactionModel();
		$this->accountModel     = new AccountModel();
		$this->accountService   = new AccountService();
		$this->ledgerModel      = new LedgerModel();
	}

	public function dashboard(){
		$wallet = $this->accountModel->where('user_id', session()->get('user_id'))->first();

		$wallet['balance'] = $this->accountService->getBalance($wallet['id']);

		return view('wallet/dashboard', [
            'wallet' => $wallet
        ]);
	}

	public function transactions(){
		$userId  = session()->get('user_id');
		$account = $this->accountModel->where('user_id', $userId)->first();

		$ledgerEntries = $this->ledgerModel
			->where('account_id', $account['id'])
			->orderBy('created_at', 'DESC')
			->paginate(10);

		$transactions = [];
		foreach($ledgerEntries as $entry){
			$transaction = $this->transactionModel->find($entry['transaction_id']);
			if($transaction){
				$transaction['entry_type'] = $entry['entry_type'];
				$transaction['entry_amount'] = $entry['amount'];
				$transactions[] = $transaction;
			}
		}

        return view('wallet/transactions', [
            'transactions' => $transactions,
            'pager'        => $this->ledgerModel->pager
        ]);
	}

	public function transactionDetail($id){
	    $userId  = session()->get('user_id');
	    $account = $this->accountModel->where('user_id', $userId)->first();

	    $transaction = $this->transactionModel->find($id);

	    if(!$transaction){
		return redirect()->to('/wallet/transactions')->with('error', 'Transaction not found');
	    }

	    $ledgerEntry = $this->ledgerModel
		->where('transaction_id', $id)
		->where('account_id', $account['id'])
		->first();

	    if(!$ledgerEntry){
		return redirect()->to('/wallet/transactions')->with('error', 'Transaction not found');
	    }

	    $db = \Config\Database::connect();

	    $ownerUser = $db->table('users')
		->where('id', $userId)
		->get()->getRowArray();

	    $otherParty = null;
	    if($transaction['type'] === 'wallet_transfer'){
		$isCredit = $ledgerEntry['entry_type'] === 'credit';

		$otherLedger = $db->table('ledger_entries l')
		    ->join('accounts a', 'l.account_id = a.id')
		    ->join('users u', 'a.user_id = u.id')
		    ->where('l.transaction_id', $id)
		    ->where('l.account_id !=', $account['id'])
		    ->where('l.entry_type', $isCredit ? 'debit' : 'credit')
		    ->where('a.user_id IS NOT NULL', null, false)
		    ->get()->getRowArray();

		if($otherLedger){
		    $otherParty = [
		        'email' => $otherLedger['email'],
		        'name'  => $otherLedger['name']
		    ];
		}
	    }

	    return view('wallet/transaction', [
		'transaction'  => $transaction,
		'entry_type'   => $ledgerEntry['entry_type'],
		'entry_amount' => $ledgerEntry['amount'] + $transaction['fee_amount'],
		'fee'          => $transaction['fee_amount'],
		'owner'        => $ownerUser,
		'other_party'  => $otherParty,
	    ]);
	}
}
