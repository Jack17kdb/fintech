<?= view('layouts/header', ['title' => 'Register - FINEX']) ?>

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

        <h1 class="card-title">Create Your Account</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?php if (is_array(session()->getFlashdata('error'))): ?>
                    <?php foreach (session()->getFlashdata('error') as $error): ?>
                        <?= esc($error) ?><br>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?= session()->getFlashdata('error') ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('register') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-row">
                <input 
                    type="text" 
                    name="name" 
                    class="form-control" 
                    placeholder="Full Name"
                    value="<?= old('name') ?>"
                    required
                >
                <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="Email"
                    value="<?= old('email') ?>"
                    required
                >
            </div>

            <div class="form-row">
                <input 
                    type="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Password"
                    required
                >
                <input 
                    type="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Confirm Password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <div class="form-footer">
            Already have an account? <a href="<?= base_url('login') ?>">Log in</a>
        </div>
    </div>
</div>

<?= view('layouts/footer') ?>
