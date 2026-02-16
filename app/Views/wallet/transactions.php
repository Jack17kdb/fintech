<?= view('layouts/header', ['title' => 'Transaction History - FINEX']) ?>

<div class="container">
    <div class="card">
        <div class="logo-section">
            <div class="logo-bg"></div>
            <div class="finex-logo">FINEX</div>
            <div class="currency-icons">
                <div class="currency-icon icon-1">€</div>
                <div class="currency-icon icon-2">¥</div>
            </div>
        </div>

        <div class="nav-tabs">
            <a href="<?= base_url('wallet') ?>" class="nav-tab">Dashboard</a>
            <a href="<?= base_url('wallet/deposit') ?>" class="nav-tab">Deposit</a>
            <a href="<?= base_url('wallet/withdraw') ?>" class="nav-tab">Withdraw</a>
            <a href="<?= base_url('wallet/transactions') ?>" class="nav-tab active">History</a>
            <a href="<?= base_url('wallet/profile') ?>" class="nav-tab">Profile</a>
        </div>

        <h1 class="card-title">Transaction History</h1>

        <?php if (!empty($transactions)): ?>
            <style>
                .transaction-list {
                    margin-top: 20px;
                }
                .transaction-item {
                    padding: 16px;
                    border: 1px solid #E2E8F0;
                    border-radius: 10px;
                    margin-bottom: 12px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .transaction-info {
                    flex: 1;
                }
                .transaction-type {
                    font-weight: 600;
                    color: #1a202c;
                    font-size: 14px;
                    margin-bottom: 4px;
                }
                .transaction-date {
                    font-size: 12px;
                    color: #718096;
                }
                .transaction-amount {
                    font-weight: 700;
                    font-size: 16px;
                }
                .amount-credit {
                    color: #2F855A;
                }
                .amount-debit {
                    color: #C53030;
                }
                .pagination {
                    margin-top: 20px;
                    text-align: center;
                }
            </style>

            <div class="transaction-list">
                <?php foreach ($transactions as $transaction): ?>
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-type"><?= esc($transaction['type']) ?></div>
                            <div class="transaction-date">
                                <?= date('M d, Y h:i A', strtotime($transaction['created_at'])) ?>
                            </div>
                        </div>
                        <div class="transaction-amount amount-debit">
                            -$<?= number_format($transaction['amount'], 2) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (isset($pager)): ?>
                <div class="pagination">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert" style="background: #F7FAFC; border: 1px solid #E2E8F0; color: #4A5568; text-align: center;">
                No transactions found
            </div>
        <?php endif; ?>

        <div style="margin-top: 20px;">
            <a href="<?= base_url('wallet') ?>" style="text-decoration: none;">
                <button class="btn btn-primary">Back to Dashboard</button>
            </a>
        </div>
    </div>
</div>

<?= view('layouts/footer') ?>
