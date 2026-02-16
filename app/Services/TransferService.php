<?php

namespace App\Services;

use App\Models\AccountModel;
use App\Models\TransactionModel;
use App\Models\LedgerModel;
use CodeIgniter\Database\Exceptions\DatabaseException;


class TransferService{
    protected $accountModel;
    protected $transactionModel;
    protected $ledgerModel;
    protected $pricingService;
    protected $db;

    public function __construct()
    {
        $this->accountModel = new AccountModel();
        $this->transactionModel = new TransactionModel();
        $this->ledgerModel = new LedgerModel();
        $this->pricingService = new PricingService();
        $this->db = \Config\Database::connect();
    }

    public function transfer($senderAccountId, $receiverAccountId, $amount, $userId){
    	if($senderAccountId == $receiverAccountId){
    		throw new \Exception("Cannot transfer to same account");
    	}

    	$accountService = new AccountService();

    	$fee = $this->pricingService->CalculateFee("wallet_transfer", $amount);
    	$totalDebit = $amount + $fee;

    	$senderBalance = $accountService->getBalance($senderAccountId);

    	if($senderBalance < $totalDebit){
    		throw new \Exception("Insufficient balance");
    	}

    	$this->db->transStart();

    	$transactionId = $this->transactionModel->insert([
    		'reference' => uniqid('TXN'),
        	'type' => 'wallet_transfer',
        	'amount' => $amount,
        	'fee_amount' => $fee,
        	'status' => 'completed',
        	'user_id' => $userId
    	]);

    	$this->ledgerModel->insert([
    		'transaction_id' => $transactionId,
        	'account_id' => $senderAccountId,
        	'amount' => $totalDebit,
        	'entry_type' => "debit"
    	]);

    	$this->ledgerModel->insert([
    		'transaction_id' => $transactionId,
        	'account_id' => $receiverAccountId,
        	'amount' => $amount,
        	'entry_type' => 'credit'
    	]);

    	$platformAccount = $this->accountModel
    		->where('account_number', 'PLATFORM_REVENUE')
    		->first();

    	if($platformAccount){
	    	$this->ledgerModel->insert([
	    		'transaction_id' => $transactionId,
	        	'account_id' => $platformAccount['id'],
	        	'amount' => $fee,
	        	'entry_type' => 'credit'
	    	]);
    	}

    	$this->db->transComplete();

    	if($this->db->transStatus() === false){
    		throw new DatabaseException("Transfer failed.");
    	}

    	return $transactionId;
    }

}
