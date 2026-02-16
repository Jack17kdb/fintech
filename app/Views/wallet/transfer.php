<?= view('layouts/header', ['title' => 'Transfer - FINEX']) ?>

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
            <a href="<?= base_url('wallet/transactions') ?>" class="nav-tab">Tricnary</a>
            <a href="<?= base_url('wallet/profile') ?>" class="nav-tab">Profile</a>
        </div>

        <div class="balance-card">
            <div class="balance-label">Current Balance</div>
            <div class="balance-amount">$<?= number_format($balance, 2) ?> KSH</div>
            <div class="money-icon">💵</div>
        </div>

        <div class="section-title">Transfer Funds</div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('wallet/transfer') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <input
                    type="number"
                    name="amount"
                    class="form-control"
                    placeholder="Amount (e.g., 100.00)"
                    step="0.01"
                    min="0.01"
                    required
                >
            </div>

            <div class="form-group">
                <input
                    type="text"
                    name="receiverEmail"
                    class="form-control"
                    placeholder="Recipient Account Email"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">Transfer</button>
        </form>
    </div>
</div>

<?= view('layouts/footer') ?>
