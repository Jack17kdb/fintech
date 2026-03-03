<?php

namespace App\Controllers;

use App\Models\DepositModel;
use App\Models\UserModel;
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
	protected $userModel;

	public function __construct(){
		$this->userModel = new UserModel();
		$this->depositModel = new DepositModel();
		$this->accountModel = new AccountModel();
		$this->transactionModel = new TransactionModel();
		$this->ledgerModel = new LedgerModel();
		$this->pricingService = new PricingService();
	}

	public function depositForm(){
	    $userId  = session()->get('user_id');
	    $account = $this->accountModel->where('user_id', $userId)->first();
	    $accountService = new \App\Services\AccountService();

	    return view('wallet/deposit', [
		'balance' => $accountService->getBalance($account['id'])
	    ]);
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

		$accountService = new \App\Services\AccountService();
		$currentBalance = $accountService->getBalance($account['id']);
	        
	        $newBalance = $currentBalance + $amount;
	        
	        $this->accountModel->update($account['id'], [
	        	'balance' => $newBalance
	        ]);
	        
	        $user = $this->userModel->find($userId);
	        
	        $fee = $this->pricingService->CalculateFee('wallet_deposit', $user['location']);

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
        		'amount' => $amount + $fee,
        		'entry_type' => 'credit'
	    	]);
	    	
	    	$platformAccount = $this->accountModel
		    ->where('account_number', 'PLATFORM_REVENUE')
		    ->first();

		if($platformAccount){
		    $this->ledgerModel->insert([
			'transaction_id' => $transactionId,
			'account_id'     => $platformAccount['id'],
			'amount'         => $fee,
			'entry_type'     => 'credit'
		    ]);
		}

	    	$db->transComplete();

        	if ($db->transStatus() === false) {
        	    return redirect()->back()->with('error', 'Transaction failed. Please try again.');
	        }

	    	$transaction = $this->transactionModel->find($transactionId);

		$owner = $this->userModel->find($userId);

		return view('wallet/transaction', [
		    'transaction'  => $transaction,
		    'fee'          => $transaction['fee_amount'],
		    'entry_type'   => 'credit',
		    'entry_amount' => $amount,
		    'owner'        => $owner,
		    'label'        => 'Deposit'
		]);
	}
}
