<?php

namespace App\Controllers;

use App\Models\AccountModel;
use App\Models\UserModel;
use App\Models\TransactionModel;
use App\Models\LedgerModel;
use App\Services\PricingService;

class WithdrawController extends BaseController{
	protected $accountModel;
	protected $transactionModel;
	protected $ledgerModel;
	protected $pricingService;
	protected $userModel;
	
	public function __construct(){
		$this->accountModel = new AccountModel();
		$this->transactionModel = new TransactionModel();
		$this->ledgerModel = new LedgerModel();
		$this->pricingService = new PricingService();
		$this->userModel = new UserModel();
	}
	
	public function withdrawForm(){
	    $userId  = session()->get('user_id');
	    $account = $this->accountModel->where('user_id', $userId)->first();
	    $accountService = new \App\Services\AccountService();

	    return view('wallet/withdraw', [
		'balance' => $accountService->getBalance($account['id'])
	    ]);
	}
	
	public function withdraw(){
		$userId = session()->get('user_id');
		$amount = (float) $this->request->getPost('amount');
		
		$account = $this->accountModel->where('user_id', $userId)->first();

	        if (!$account) {
        	    return redirect()->back()->with('error', 'No account found!!');
	        }
	        
	        $db = \Config\Database::connect();
	        
	        $db->transStart();
	        
	        $accountService = new \App\Services\AccountService();
		$currentBalance = $accountService->getBalance($account['id']);
	        
	        if($amount > $currentBalance){
	        	return redirect()
	        		->back()
	        		->with('error', 'Amount cannot be greater than the account balance');
	        };
	        
	        $newBalance = $currentBalance - $amount;
	        
	        $this->accountModel->update($account['id'], [
	        	'balance' => $newBalance
	        ]);
	        
	        $user = $this->userModel->find($userId);
	        
	        $fee = $this->pricingService->CalculateFee('wallet_deposit', $user['location']);
	        
	        $transactionId = $this->transactionModel->insert([
    			'reference' => uniqid('TXN'),
        		'type' => 'wallet_withdrawal',
        		'amount' => $amount,
        		'fee_amount' => $fee,
        		'status' => 'completed',
        		'user_id' => $userId
	    	]);

	    	$this->ledgerModel->insert([
    			'transaction_id' => $transactionId,
        		'account_id' => $account['id'],
        		'amount' => $amount + $fee,
        		'entry_type' => 'debit'
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
		    'entry_type'   => 'debit',
		    'entry_amount' => $amount + $fee,
		    'owner'        => $owner,
		    'label'        => 'Withdrawal'
		]);
	}
}
