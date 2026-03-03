<?php require_once APPPATH . 'Views/layouts/admin_header.php'; ?>
<body class="hold-transition register-page dark-mode">
<style>
    body{
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .register-title{
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        color: white;
    }
    .register-box{
        width: 400px;
    }
</style>

<h1 class="text-center py-4 register-title"><b>FINEX Wallet System</b></h1>

<div class="register-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="<?= base_url('register') ?>" class="h1"><b>Register</b></a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Create your new wallet account</p>

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
                <div class="input-group mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Full Name" value="<?= old('name') ?>" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="<?= old('email') ?>" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="text" name="location" class="form-control" placeholder="Location" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <a href="<?= base_url('login') ?>" class="btn btn-default btn-block">Login</a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-success btn-block">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
