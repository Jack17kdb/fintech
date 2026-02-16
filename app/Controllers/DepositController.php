<?php

namespace App\Controllers;

use App\Models\DepositModel;
use App\Models\AccountModel;
use App\Models\TransactionModel;
use App\Models\LedgerModel;
use App\Services\PricingService;

class DepositController extends BaseController{
	protected $depositModel;
	protected $accountModel;
	protected $transactionModel;
	protected $ledgerModel;
	protected $pricingService;

	public function __construct(){
		$this->depositModel = new DepositModel();
		$this->accountModel = new AccountModel();
		$this->transactionModel = new TransactionModel();
		$this->ledgerModel = new LedgerModel();
		$this->pricingService = new PricingService();
	}

	public function depositForm(){
		return view('wallet/deposit');
	}

	public function deposit(){
		$userId = session()->get('user_id');
	        $amount = (float) $this->request->getPost('amount');

		if ($amount <= 0) {
        	    return redirect()->back()->with('error', 'Please enter a valid amount.');
	        }

	        $account = $this->accountModel->where('user_id', $userId)->first();

	        if (!$account) {
        	    return redirect()->back()->with('error', 'No account found!!');
	        }

	        $db = \Config\Database::connect();
	        $db->transStart();

		$newBalance = $account['balance'] + $amount;

		$this->accountModel->update($account['id'], [
        	    'balance' => $newBalance
	        ]);
	        
	        $fee = $this->pricingService->CalculateFee('wallet_withdraw', $amount);

	        $transactionId = $this->transactionModel->insert([
    			'reference' => uniqid('TXN'),
        		'type' => 'wallet_deposit',
        		'amount' => $amount,
        		'fee_amount' => $fee,
        		'status' => 'completed',
        		'user_id' => $userId
	    	]);

	    	$this->ledgerModel->insert([
    			'transaction_id' => $transactionId,
        		'account_id' => $account['id'],
        		'amount' => $amount,
        		'entry_type' => 'credit'
	    	]);

	    	$db->transComplete();

        	if ($db->transStatus() === false) {
        	    return redirect()->back()->with('error', 'Transaction failed. Please try again.');
	        }

	    	$transaction = $this->transactionModel->find($transactionId);

		return view('wallet/transaction', [
        	    'transaction' => $transaction,
                    'fee' => $transaction['fee_amount']
	        ]);
	}
}
