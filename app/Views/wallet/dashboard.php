<?= view('layouts/header', ['title' => 'Dashboard - FINEX']) ?>

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
            <a href="<?= base_url('wallet') ?>" class="nav-tab active">Dashboard</a>
            <a href="<?= base_url('wallet/deposit') ?>" class="nav-tab">Deposit</a>
            <a href="<?= base_url('wallet/withdraw') ?>" class="nav-tab">Withdraw</a>
            <a href="<?= base_url('wallet/transactions') ?>" class="nav-tab">History</a>
        </div>

        <div class="balance-card">
            <div class="balance-label">Current Balance</div>
            <div class="balance-amount">$<?= number_format($wallet['balance'] ?? 0, 2) ?> KSH</div>
            <div class="money-icon">💵</div>
        </div>

        <div class="section-title">Quick Actions</div>

        <div style="display: grid; gap: 12px;">
            <a href="<?= base_url('wallet/deposit') ?>" style="text-decoration: none;">
                <button class="btn btn-primary">Deposit Funds</button>
            </a>
            <a href="<?= base_url('wallet/transfer') ?>" style="text-decoration: none;">
                <button class="btn btn-primary">Transfer Money</button>
            </a>
            <a href="<?= base_url('wallet/transactions') ?>" style="text-decoration: none;">
                <button class="btn btn-primary">View History</button>
            </a>
        </div>

        <div class="form-footer" style="margin-top: 24px;">
            <a href="<?= base_url('logout') ?>">Logout</a>
        </div>
    </div>
</div>

<?= view('layouts/footer') ?>
