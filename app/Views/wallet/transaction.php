<?= view('layouts/header', ['title' => 'Transaction Details - FINEX']) ?>

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

        <h1 class="card-title">Transaction Successful</h1>

        <div class="alert alert-success">
            Your transaction has been completed successfully!
        </div>

        <style>
            .detail-row {
                display: flex;
                justify-content: space-between;
                padding: 14px 0;
                border-bottom: 1px solid #E2E8F0;
            }
            .detail-row:last-child {
                border-bottom: none;
            }
            .detail-label {
                color: #718096;
                font-size: 14px;
                font-weight: 500;
            }
            .detail-value {
                color: #1a202c;
                font-size: 14px;
                font-weight: 600;
            }
        </style>

        <div style="margin: 24px 0;">
            <div class="detail-row">
                <span class="detail-label">Reference</span>
                <span class="detail-value"><?= esc($transaction['reference']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Amount</span>
                <span class="detail-value">$<?= number_format($transaction['amount'], 2) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Fee</span>
                <span class="detail-value">$<?= number_format($fee ?? $transaction['fee_amount'] ?? 0, 2) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total</span>
                <span class="detail-value">$<?= number_format($transaction['amount'] + $fee ?? $transaction['fee_amount'] ?? 0, 2) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value" style="color: #2F855A;"><?= esc($transaction['status']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value">
                    <?= date('M d, Y h:i A', strtotime($transaction['created_at'])) ?>
                </span>
            </div>
        </div>

        <div style="display: grid; gap: 12px;">
            <a href="<?= base_url('wallet') ?>" style="text-decoration: none;">
                <button class="btn btn-primary">Back to Dashboard</button>
            </a>
            <a href="<?= base_url('wallet/transactions') ?>" style="text-decoration: none;">
                <button class="btn btn-primary">View All Transactions</button>
            </a>
        </div>
    </div>
</div>

<?= view('layouts/footer') ?>
