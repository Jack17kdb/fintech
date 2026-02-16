<?= view('layouts/header', ['title' => 'Deposit - FINEX']) ?>

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

        <h1 class="card-title">Deposit Funds</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('wallet/deposit') ?>" method="POST">
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

            <button type="submit" class="btn btn-primary">Deposit</button>
        </form>

        <div class="form-footer">
            <a href="<?= base_url('wallet') ?>">Back to Dashboard</a>
        </div>
    </div>
</div>

<?= view('layouts/footer') ?>
