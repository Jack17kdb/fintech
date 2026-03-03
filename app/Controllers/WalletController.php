<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\AccountModel;
use App\Models\LedgerModel;
use App\Services\AccountService;
use Dompdf\Dompdf;
use Dompdf\Options;


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
		
		if (!$wallet) {
	        	return redirect()->to('/login')->with('error', 'Wallet not found.');
		}

		return view('wallet/dashboard', [
            'wallet' => $wallet
        ]);
	}

	public function transactions() {
	    $userId = session()->get('user_id');
	    $name = session()->get('user_name');

	    if (!$userId) {
		return redirect()->to('/login');
	    }

	    $account = $this->accountModel
		->where('user_id', $userId)
		->first();

	    if (!$account) {
		return redirect()->back()->with('error', 'Account not found.');
	    }

	    $dateFrom = $this->request->getGet('date_from') ?? date('Y-m-d', strtotime('-1 month'));
	    $dateTo   = $this->request->getGet('date_to') ?? date('Y-m-d');
	    $keyword  = trim($this->request->getGet('keyword') ?? '');

	    $db = \Config\Database::connect();

	    $builder = $db->table('ledger_entries l')
		->select('
		    l.id as ledger_id,
		    l.amount as entry_amount,
		    l.entry_type,
		    l.created_at,

		    t.id as transaction_id,
		    t.reference,
		    t.type,
		    t.status,
		    t.fee_amount,

		    COALESCE(other_u.name, other_u.email) as party_name
		')
		->join('transactions t', 'l.transaction_id = t.id')

		// Proper correlated join to get the other ledger entry
		->join(
		    'ledger_entries other_l',
		    'other_l.transaction_id = l.transaction_id 
		     AND other_l.account_id != l.account_id
		     AND other_l.entry_type != l.entry_type',
		    'left'
		)

		->join('accounts other_a', 'other_l.account_id = other_a.id', 'left')
		->join('users other_u', 'other_a.user_id = other_u.id', 'left')

		// Strict isolation: ONLY this user's ledger entries
		->where('l.account_id', $account['id'])

		// Proper datetime filtering (index friendly)
		->where('l.created_at >=', $dateFrom . ' 00:00:00')
		->where('l.created_at <=', $dateTo . ' 23:59:59');

	    if (!empty($keyword)) {
		$builder->groupStart()
		    ->like('t.reference', $keyword)
		    ->orLike('t.type', $keyword)
		    ->orLike('other_u.name', $keyword)
		    ->orLike('other_u.email', $keyword)
		->groupEnd();
	    }

	    $transactions = $builder
		->orderBy('l.created_at', 'DESC')
		->get()
		->getResultArray();

	    return view('wallet/transactions', [
		'transactions' => $transactions,
		'date_from'    => $dateFrom,
		'date_to'      => $dateTo,
		'keyword'      => $keyword,
		'username'     => $name
	    ]);
	}

	public function exportCSV(){
	    $userId  = session()->get('user_id');
	    $account = $this->accountModel->where('user_id', $userId)->first();

	    
	    $dateFrom = $this->request->getGet('date_from') ?? date('Y-m-d', strtotime('-1 month'));
	    $dateTo   = $this->request->getGet('date_to') ?? date('Y-m-d');
	    $keyword  = $this->request->getGet('keyword') ?? '';

	    $db = \Config\Database::connect();

	    $builder = $db->table('ledger_entries l')
		->select('
		    l.id as ledger_id,
		    l.amount as entry_amount,
		    l.entry_type,
		    l.created_at,

		    t.id as transaction_id,
		    t.reference,
		    t.type,
		    t.status,
		    t.fee_amount,

		    COALESCE(other_u.name, other_u.email) as party_name
		')
		->join('transactions t', 'l.transaction_id = t.id')

		// Proper correlated join to get the other ledger entry
		->join(
		    'ledger_entries other_l',
		    'other_l.transaction_id = l.transaction_id 
		     AND other_l.account_id != l.account_id
		     AND other_l.entry_type != l.entry_type',
		    'left'
		)

		->join('accounts other_a', 'other_l.account_id = other_a.id', 'left')
		->join('users other_u', 'other_a.user_id = other_u.id', 'left')

		// Strict isolation: ONLY this user's ledger entries
		->where('l.account_id', $account['id'])

		// Proper datetime filtering (index friendly)
		->where('l.created_at >=', $dateFrom . ' 00:00:00')
		->where('l.created_at <=', $dateTo . ' 23:59:59');

	    if (!empty($keyword)) {
		$builder->groupStart()
		    ->like('t.reference', $keyword)
		    ->orLike('t.type', $keyword)
		    ->orLike('other_u.name', $keyword)
		    ->orLike('other_u.email', $keyword)
		->groupEnd();
	    }

	    $transactions = $builder
		->orderBy('l.created_at', 'DESC')
		->get()
		->getResultArray();

	    
	    $filename = 'transactions_' . date('Y-m-d_His') . '.csv';
	    header('Content-Type: text/csv');
	    header('Content-Disposition: attachment; filename="' . $filename . '"');

	    $output = fopen('php://output', 'w');

	    
	    fputcsv($output, ['Date', 'Type', 'Reference', 'Party', 'Amount', 'Fee', 'Status']);

	    
	    foreach($transactions as $t){
	        $isCredit = $t['entry_type'] === 'credit';
	        $sign = $isCredit ? '+' : '-';
	        
	        $label = match($t['type']) {
	            'wallet_deposit' => 'Deposit',
	            'wallet_transfer' => $isCredit ? 'Transfer Received' : 'Transfer Sent',
	            'wallet_withdrawal' => 'Withdrawal',
	            default => ucwords(str_replace('_', ' ', $t['type']))
	        };

	        fputcsv($output, [
	            date('Y-m-d H:i', strtotime($t['created_at'])),
	            $label,
	            $t['reference'],
	            $t['party_name'] ?? 'N/A',
	            $sign . 'KSH ' . number_format($t['entry_amount'], 2),
	            'KSH ' . number_format($t['fee_amount'], 2),
	            $t['status']
	        ]);
	    }

	    fclose($output);
	    exit;
	}

	public function exportPDF(){
	    $userId  = session()->get('user_id');
	    $account = $this->accountModel->where('user_id', $userId)->first();
	    
	    
	    $db = \Config\Database::connect();
	    $user = $db->table('users')->where('id', $userId)->get()->getRowArray();

	    
	    $dateFrom = $this->request->getGet('date_from') ?? date('Y-m-d', strtotime('-1 month'));
	    $dateTo   = $this->request->getGet('date_to') ?? date('Y-m-d');
	    $keyword  = $this->request->getGet('keyword') ?? '';

	    $builder = $db->table('ledger_entries l')
		->select('
		    l.id as ledger_id,
		    l.amount as entry_amount,
		    l.entry_type,
		    l.created_at,

		    t.id as transaction_id,
		    t.reference,
		    t.type,
		    t.status,
		    t.fee_amount,

		    COALESCE(other_u.name, other_u.email) as party_name
		')
		->join('transactions t', 'l.transaction_id = t.id')

		// Proper correlated join to get the other ledger entry
		->join(
		    'ledger_entries other_l',
		    'other_l.transaction_id = l.transaction_id 
		     AND other_l.account_id != l.account_id',
		    'left'
		)

		->join('accounts other_a', 'other_l.account_id = other_a.id', 'left')
		->join('users other_u', 'other_a.user_id = other_u.id', 'left')

		// Strict isolation: ONLY this user's ledger entries
		->where('l.account_id', $account['id'])

		// Proper datetime filtering (index friendly)
		->where('l.created_at >=', $dateFrom . ' 00:00:00')
		->where('l.created_at <=', $dateTo . ' 23:59:59');

	    if (!empty($keyword)) {
		$builder->groupStart()
		    ->like('t.reference', $keyword)
		    ->orLike('t.type', $keyword)
		    ->orLike('other_u.name', $keyword)
		    ->orLike('other_u.email', $keyword)
		->groupEnd();
	    }

	    $transactions = $builder
		->orderBy('l.created_at', 'DESC')
		->get()
		->getResultArray();

	    
	    $totalCredit = 0;
	    $totalDebit = 0;
	    $totalFees = 0;

	    foreach($transactions as $t){
	        if($t['entry_type'] === 'credit'){
	            $totalCredit += $t['entry_amount'];
	        } else {
	            $totalDebit += $t['entry_amount'];
	        }
	        $totalFees += $t['fee_amount'];
	    }

	    // Generate HTML for PDF
	    $html = '
	    <!DOCTYPE html>
	    <html>
	    <head>
	        <meta charset="utf-8">
	        <title>Transaction Report</title>
	        <style>
	            @page {
	                margin: 20px;
	            }
	            body {
	                font-family: Arial, sans-serif;
	                font-size: 11px;
	                color: #333;
	            }
	            .header {
	                text-align: center;
	                margin-bottom: 30px;
	                border-bottom: 3px solid #4F7FFF;
	                padding-bottom: 15px;
	            }
	            .header h1 {
	                color: #4F7FFF;
	                margin: 0;
	                font-size: 28px;
	            }
	            .header p {
	                margin: 5px 0;
	                color: #666;
	            }
	            .info-section {
	                margin-bottom: 20px;
	                padding: 10px;
	                background: #f8f9fa;
	                border-radius: 5px;
	            }
	            .info-section table {
	                width: 100%;
	            }
	            .info-section td {
	                padding: 5px;
	            }
	            .info-label {
	                font-weight: bold;
	                width: 150px;
	            }
	            table.transactions {
	                width: 100%;
	                border-collapse: collapse;
	                margin-top: 20px;
	            }
	            table.transactions th {
	                background-color: #4F7FFF;
	                color: white;
	                padding: 10px;
	                text-align: left;
	                font-size: 10px;
	            }
	            table.transactions td {
	                padding: 8px;
	                border-bottom: 1px solid #ddd;
	                font-size: 10px;
	            }
	            table.transactions tr:nth-child(even) {
	                background-color: #f8f9fa;
	            }
	            .text-success {
	                color: #28a745;
	                font-weight: bold;
	            }
	            .text-danger {
	                color: #dc3545;
	                font-weight: bold;
	            }
	            .summary {
	                margin-top: 30px;
	                padding: 15px;
	                background: #f8f9fa;
	                border-radius: 5px;
	            }
	            .summary-row {
	                display: flex;
	                justify-content: space-between;
	                margin: 8px 0;
	                font-size: 12px;
	            }
	            .summary-label {
	                font-weight: bold;
	            }
	            .footer {
	                margin-top: 30px;
	                text-align: center;
	                font-size: 9px;
	                color: #666;
	                border-top: 1px solid #ddd;
	                padding-top: 10px;
	            }
	        </style>
	    </head>
	    <body>
	        <div class="header">
	            <h1>FINEX WALLET</h1>
	            <p>Transaction Report</p>
	        </div>

	        <div class="info-section">
	            <table>
	                <tr>
	                    <td class="info-label">Account Holder:</td>
	                    <td>'.htmlspecialchars($user['name']).'</td>
	                    <td class="info-label">Account Number:</td>
	                    <td>'.htmlspecialchars($account['account_number']).'</td>
	                </tr>
	                <tr>
	                    <td class="info-label">Email:</td>
	                    <td>'.htmlspecialchars($user['email']).'</td>
	                    <td class="info-label">Report Date:</td>
	                    <td>'.date('F d, Y H:i A').'</td>
	                </tr>
	                <tr>
	                    <td class="info-label">Period:</td>
	                    <td colspan="3">'.date('F d, Y', strtotime($dateFrom)).' to '.date('F d, Y', strtotime($dateTo)).'</td>
	                </tr>';
	    
	    if(!empty($keyword)){
	        $html .= '<tr>
	                    <td class="info-label">Search Keyword:</td>
	                    <td colspan="3">'.htmlspecialchars($keyword).'</td>
	                </tr>';
	    }
	    
	    $html .= '</table>
	        </div>

	        <table class="transactions">
	            <thead>
	                <tr>
	                    <th width="12%">Date</th>
	                    <th width="15%">Type</th>
	                    <th width="18%">Reference</th>
	                    <th width="20%">Party</th>
	                    <th width="15%">Amount</th>
	                    <th width="10%">Fee</th>
	                    <th width="10%">Status</th>
	                </tr>
	            </thead>
	            <tbody>';

	    if(empty($transactions)){
	        $html .= '<tr><td colspan="7" style="text-align:center; padding: 20px;">No transactions found</td></tr>';
	    } else {
	        foreach($transactions as $t){
	            $isCredit = $t['entry_type'] === 'credit';
	            $sign = $isCredit ? '+' : '-';
	            $class = $isCredit ? 'text-success' : 'text-danger';
	            
	            $label = match($t['type']) {
	                'wallet_deposit' => 'Deposit',
	                'wallet_transfer' => $isCredit ? 'Transfer Received' : 'Transfer Sent',
	                'wallet_withdrawal' => 'Withdrawal',
	                default => ucwords(str_replace('_', ' ', $t['type']))
	            };

	            $html .= '<tr>
	                <td>'.date('d/m/Y H:i', strtotime($t['created_at'])).'</td>
	                <td>'.$label.'</td>
	                <td>'.htmlspecialchars($t['reference']).'</td>
	                <td>'.htmlspecialchars($t['party_name'] ?? 'N/A').'</td>
	                <td class="'.$class.'">'.$sign.'KSH '.number_format($t['entry_amount'], 2).'</td>
	                <td>KSH '.number_format($t['fee_amount'], 2).'</td>
	                <td>'.ucfirst($t['status']).'</td>
	            </tr>';
	        }
	    }

	    $html .= '</tbody>
	        </table>

	        <div class="summary">
	            <h3 style="margin-top:0; color: #4F7FFF;">Summary</h3>
	            <div class="summary-row">
	                <span class="summary-label">Total Transactions:</span>
	                <span>'.count($transactions).'</span>
	            </div>
	            <div class="summary-row">
	                <span class="summary-label">Total Credits (Money In):</span>
	                <span class="text-success">+KSH '.number_format($totalCredit, 2).'</span>
	            </div>
	            <div class="summary-row">
	                <span class="summary-label">Total Debits (Money Out):</span>
	                <span class="text-danger">-KSH '.number_format($totalDebit, 2).'</span>
	            </div>
	            <div class="summary-row">
	                <span class="summary-label">Total Fees Paid:</span>
	                <span>KSH '.number_format($totalFees, 2).'</span>
	            </div>
	            <div class="summary-row" style="border-top: 2px solid #4F7FFF; margin-top: 10px; padding-top: 10px; font-size: 14px;">
	                <span class="summary-label">Net Change:</span>
	                <span class="'.($totalCredit - $totalDebit >= 0 ? 'text-success' : 'text-danger').'">
	                    '.($totalCredit - $totalDebit >= 0 ? '+' : '').'KSH '.number_format($totalCredit - $totalDebit, 2).'
	                </span>
	            </div>
	        </div>

	        <div class="footer">
	            <p>This is a computer-generated document. No signature is required.</p>
	            <p>FINEX Wallet System | Generated on '.date('F d, Y H:i:s A').'</p>
	        </div>
	    </body>
	    </html>';

	    // Initialize DomPDF
	    $options = new Options();
	    $options->set('isHtml5ParserEnabled', true);
	    $options->set('isRemoteEnabled', true);
	    $options->set('defaultFont', 'Arial');
	    
	    $dompdf = new Dompdf($options);
	    $dompdf->loadHtml($html);
	    $dompdf->setPaper('A4', 'portrait');
	    $dompdf->render();

	    // Output PDF
	    $filename = 'FINEX_Transactions_' . date('Y-m-d_His') . '.pdf';
	    $dompdf->stream($filename, array("Attachment" => true));
	    exit;
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
		'entry_amount' => $ledgerEntry['amount'],
		'fee'          => $transaction['fee_amount'],
		'owner'        => $ownerUser,
		'other_party'  => $otherParty,
	    ]);
	}
}
