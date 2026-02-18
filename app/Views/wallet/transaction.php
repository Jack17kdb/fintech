<?= view('layouts/header', ['title' => 'Transaction Receipt - FINEX']) ?>

<div class="container">
    <div class="card">

        <?php
            $isCredit = $entry_type === 'credit';
            $sign     = $isCredit ? '+' : '-';
            $color    = $isCredit ? '#2F855A' : '#C53030';

            $label = match($transaction['type']) {
                'wallet_deposit'    => 'Deposit',
                'wallet_transfer'   => $isCredit ? 'Money Received' : 'Money Sent',
                'wallet_withdrawal' => 'Withdrawal',
                default             => ucwords(str_replace('_', ' ', $transaction['type']))
            };
        ?>

        <style>
            .receipt-header {
                background: linear-gradient(135deg, #4F7FFF 0%, #5B8EFF 100%);
                margin: -40px -35px 28px;
                padding: 28px 35px;
                border-radius: 24px 24px 0 0;
                text-align: center;
                color: white;
            }
            .receipt-header .brand {
                font-size: 13px;
                font-weight: 700;
                opacity: 0.85;
                letter-spacing: 0.1em;
                text-transform: uppercase;
                margin-bottom: 6px;
            }
            .receipt-header .receipt-type {
                font-size: 15px;
                opacity: 0.9;
                font-weight: 500;
            }
            .receipt-message {
                background: #F7FAFC;
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                padding: 20px;
                font-size: 15px;
                line-height: 1.85;
                color: #2D3748;
                margin-bottom: 24px;
            }
            .receipt-message .highlight {
                font-weight: 700;
                color: #1a202c;
            }
            .receipt-message .amount {
                font-weight: 700;
                color: <?= $color ?>;
            }
            .receipt-ref {
                text-align: center;
                font-size: 11px;
                color: #A0AEC0;
                font-family: monospace;
                margin-top: 16px;
                letter-spacing: 0.05em;
            }
        </style>

        <div class="receipt-header">
            <div class="brand">◉ FINEX</div>
            <div class="receipt-type"><?= $label ?> Confirmation</div>
        </div>

        <div class="receipt-message">
            <?php if($transaction['type'] === 'wallet_deposit'): ?>

                <span class="highlight"><?= esc($owner['name']) ?></span>,
                your FINEX wallet has been credited with
                <span class="amount">KSH <?= number_format($transaction['amount'], 2) ?></span>
                on <span class="highlight"><?= date('d/m/Y', strtotime($transaction['created_at'])) ?></span>
                at <span class="highlight"><?= date('h:i A', strtotime($transaction['created_at'])) ?></span>.
                Your account <span class="highlight">(<?= esc($owner['email']) ?>)</span>
                has been funded successfully.
                Transaction cost <span class="highlight">KSH 0.00</span>.

            <?php elseif($transaction['type'] === 'wallet_withdrawal'): ?>

                <span class="highlight"><?= esc($owner['name']) ?></span>,
                <span class="amount">KSH <?= number_format($transaction['amount'], 2) ?></span>
                has been withdrawn from your FINEX wallet on
                <span class="highlight"><?= date('d/m/Y', strtotime($transaction['created_at'])) ?></span>
                at <span class="highlight"><?= date('h:i A', strtotime($transaction['created_at'])) ?></span>.
                Transaction cost
                <span class="highlight">KSH <?= number_format($transaction['fee_amount'], 2) ?></span>.
                Total deducted
                <span class="amount">KSH <?= number_format($entry_amount, 2) ?></span>.

            <?php elseif($transaction['type'] === 'wallet_transfer' && !$isCredit): ?>

                <span class="highlight"><?= esc($owner['name']) ?></span>,
                you have sent
                <span class="amount">KSH <?= number_format($transaction['amount'], 2) ?></span>
                to <span class="highlight"><?= $other_party ? esc($other_party['name']) : 'recipient' ?></span>
                <?php if($other_party): ?>
                    (<span class="highlight"><?= esc($other_party['email']) ?></span>)
                <?php endif; ?>
                on <span class="highlight"><?= date('d/m/Y', strtotime($transaction['created_at'])) ?></span>
                at <span class="highlight"><?= date('h:i A', strtotime($transaction['created_at'])) ?></span>.
                Transaction cost
                <span class="highlight">KSH <?= number_format($transaction['fee_amount'], 2) ?></span>.
                Total deducted from your account
                <span class="amount">KSH <?= number_format($entry_amount, 2) ?></span>.

            <?php elseif($transaction['type'] === 'wallet_transfer' && $isCredit): ?>

                <span class="highlight"><?= esc($owner['name']) ?></span>,
                you have received
                <span class="amount">KSH <?= number_format($transaction['amount'], 2) ?></span>
                from <span class="highlight"><?= $other_party ? esc($other_party['name']) : 'sender' ?></span>
                <?php if($other_party): ?>
                    (<span class="highlight"><?= esc($other_party['email']) ?></span>)
                <?php endif; ?>
                on <span class="highlight"><?= date('d/m/Y', strtotime($transaction['created_at'])) ?></span>
                at <span class="highlight"><?= date('h:i A', strtotime($transaction['created_at'])) ?></span>.
                Your FINEX wallet has been credited
                <span class="amount">KSH <?= number_format($entry_amount, 2) ?></span>.

            <?php endif; ?>

            <div class="receipt-ref">
                Ref: <?= esc($transaction['reference']) ?>
            </div>
        </div>

        <div style="display: grid; gap: 10px;">
            <a href="<?= base_url('wallet/transactions') ?>" style="text-decoration: none;">
                <button class="btn btn-primary">Back to History</button>
            </a>
            <a href="<?= base_url('wallet') ?>" style="text-decoration: none;">
                <button class="btn btn-primary">Back to Dashboard</button>
            </a>
        </div>

    </div>
</div>

<?= view('layouts/footer') ?>
