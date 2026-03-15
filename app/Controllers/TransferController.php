<?php

namespace App\Controllers;

use App\Services\TransferService;
use App\Services\PricingService;
use App\Models\AccountModel;
use App\Models\TransactionModel;
use App\Models\UserModel;

class TransferController extends BaseController {
    protected $transferService;
    protected $pricingService;
    protected $accountModel;
    protected $transactionModel;
    protected $userModel;

    public function __construct() {
        $this->transferService = new TransferService();
        $this->pricingService = new PricingService();
        $this->accountModel = new AccountModel();
        $this->transactionModel = new TransactionModel();
        $this->userModel = new UserModel();
    }

    public function transferForm() {
	$userId = session()->get('user_id');
	$users = $this->userModel->findAll();
	$account = $this->accountModel->where('user_id', $userId)->first();
	$accountService = new \App\Services\AccountService();

        return view('wallet/transfer', [
		'balance' => $accountService->getBalance($account['id']),
		'users' => $users
	]);
    }

    public function transfer() {
        $userId = session()->get('user_id');
        $account = $this->accountModel->where('user_id', $userId)->first();

        if (!$account) {
            return redirect()->back()->with('error', 'Account not found');
        }

        $senderAccountId = $account['id'];
        $receiverName = $this->request->getPost('receiverName');
        $amount = (float)$this->request->getPost('amount');

	$receiverUser = $this->userModel->where('name', $receiverName)->first();

	if (!$receiverUser) {
	    return redirect()->back()->with('error', 'Recipient not found');
	}

	$receiverAccount = $this->accountModel->where('user_id', $receiverUser['id'])->first();
	$receiverAccountId = $receiverAccount['id'];

        try {

            $transactionId = $this->transferService->transfer($senderAccountId, $receiverAccountId, $receiverUser['location'], $amount, $userId);
            $transaction = $this->transactionModel->find($transactionId);

            $owner = $this->userModel->find($userId);

            return view('wallet/transaction', [
                'transaction'  => $transaction,
                'fee'          => $transaction['fee_amount'],
                'entry_type'   => 'debit',
                'entry_amount' => $amount + $transaction['fee_amount'],
                'owner'        => $owner,
                'other_party'  => $receiverUser,
                'label'        => 'Transfer'
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
