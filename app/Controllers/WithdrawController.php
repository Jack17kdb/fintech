<?php

namespace App\Controllers;

use App\Models\AccountModel;
use App\Models\TransactionModel;
use App\Models\LedgerModel;
use App\Services\PricingService;

class WithdrawController extends BaseController{
	protected $accountModel;
	protected $transactionModel;
	protected $ledgerModel;
	protected $pricingService;
	
	public function __construct(){
		$this->accountModel = new AccountModel();
		$this->transactionModel = new TransactionModel();
		$this->ledgerModel = new LedgerModel();
		$this->pricingService = new PricingService();
	}
	
	public function withdrawForm(){
		$account = $this->accountModel->where('user_id', session()->get('user_id'));
	
		return view('wallet/withdraw', [
			'balance' => $account['balance']
		]);
	}
	
	public function withdraw(){
		$userId = session()->get('user_id');
		$amount = (float) $this->request->getPost('amount');
		
		$account = $this->accountModel->where('user_id', $userId)->first();

	        if (!$account) {
        	    return redirect()->back()->with('error', 'No account found!!');
	        }
	        
	        $db = \Config\Database::connect;
	        
	        $db->transStart();
	        
	        if($amount > $account['balance']){
	        	return redirect()
	        		->back()
	        		->with('error', 'Amount cannot be greater than the account balance');
	        }
	        
	        $newBalance = $account['balance'] - $newBalance;
	        
	        $this->accountModel->update($account['id'], [
	        	'balance' => $newBalance
	        ]);
	        
	        $fee = $this->pricingService->CalculateFee('wallet_withdraw', $amount);
	        
	        $transactionId = $this->transactionModel->insert([
    			'reference' => uniqid('TXN'),
        		'type' => 'wallet_withdraw',
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
