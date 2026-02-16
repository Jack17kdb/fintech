<?= view('layouts/header', ['title' => 'Login - FINEX']) ?>

<div class="container">
    <div class="card">
        <div class="logo-section">
            <div class="logo-bg"></div>
            <div class="finex-logo">FINEX</div>
            <div class="currency-icons">
                <div class="currency-icon icon-1">€</div>
                <div class="currency-icon icon-2">¥</div>
                <div class="currency-icon icon-3">$</div>
            </div>
        </div>

        <h1 class="card-title">Welcome Back</h1>

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

        <form action="<?= base_url('login') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="Email"
                    value="<?= old('email') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <input 
                    type="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">Log in</button>
        </form>

        <div class="form-footer">
            Dont have an account? <a href="<?= base_url('register') ?>">Register</a>
        </div>
    </div>
</div>

<?= view('layouts/footer') ?>
