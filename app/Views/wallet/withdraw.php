<?= view('layouts/header', ['title' => 'Withdraw - FINEX']) ?>

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
            <a href="<?= base_url('wallet/withdraw') ?>" class="nav-tab active">Withdraw</a>
            <a href="<?= base_url('wallet/transactions') ?>" class="nav-tab">History</a>
            <a href="<?= base_url('wallet/profile') ?>" class="nav-tab">Profile</a>
        </div>

        <div class="balance-card">
            <div class="balance-label">Current Balance</div>
            <div class="balance-amount">$<?= number_format($balance ?? 0, 2) ?> KSH</div>
            <div class="money-icon">💵</div>
        </div>

        <div class="section-title">Withdraw Funds</div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('wallet/withdraw') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <input 
                    type="number" 
                    name="amount" 
                    class="form-control" 
                    placeholder="Amount"
                    step="0.01"
                    min="0.01"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">Withdraw</button>
        </form>
    </div>
</div>

<?= view('layouts/footer') ?>
