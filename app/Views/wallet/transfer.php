<?php require_once APPPATH . 'Views/layouts/admin_header.php'; ?>
<body class="hold-transition sidebar-mini layout-fixed dark-mode">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('logout') ?>">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="<?= base_url('wallet') ?>" class="brand-link">
            <i class="fas fa-wallet brand-image ml-3"></i>
            <span class="brand-text font-weight-light">FINEX Wallet</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a href="#" class="d-block"><?= esc(session()->get('user_name') ?? 'User') ?></a>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="<?= base_url('wallet') ?>" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('wallet/transactions') ?>" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Transactions</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('wallet/deposit') ?>" class="nav-link">
                            <i class="nav-icon fas fa-arrow-down"></i>
                            <p>Deposit</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('wallet/withdraw') ?>" class="nav-link">
                            <i class="nav-icon fas fa-arrow-up"></i>
                            <p>Withdraw</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('wallet/transfer') ?>" class="nav-link active">
                            <i class="nav-icon fas fa-exchange-alt"></i>
                            <p>Transfer</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('wallet/profile') ?>" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Profile</p>
                        </a>
                    </li>

                    <?php if(session()->get('role') == 'admin'): ?>
                    <li class="nav-header">ADMINISTRATION</li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/users') ?>" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Manage Users</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/feerules') ?>" class="nav-link">
                            <i class="nav-icon fas fa-dollar-sign"></i>
                            <p>Fee Rules</p>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Transfer Money</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Available Balance</h3>
                            </div>
                            <div class="card-body">
                                <h2 class="text-primary">KSH <?= number_format($balance ?? 0, 2) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Transfer Form</h3>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger m-3">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('wallet/transfer') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="amount">Amount (KSH)</label>
                                <input type="number" name="amount" class="form-control" id="amount" placeholder="Enter amount" step="0.01" min="0.01" required>
                            </div>
                            <div class="form-group">
                                <label>Recipient</label>
                                <!-- Hidden select submitted with form -->
                                <select name="receiverName" id="receiverName" style="display:none;" required>
                                    <option value=""></option>
                                    <?php
                                    $currentUserId = session()->get('user_id');
                                    foreach ($users as $u):
                                        if ($u['id'] == $currentUserId) continue;
                                    ?>
                                        <option value="<?= esc($u['name']) ?>"><?= esc($u['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <!-- Custom dropdown trigger -->
                                <div id="tr-user-dropdown" style="position:relative;">
                                    <div id="tr-user-trigger" style="
                                        display:flex; align-items:center; justify-content:space-between;
                                        padding:8px 12px; background:#343a40; border:1px solid #6c757d;
                                        border-radius:4px; cursor:pointer; color:#adb5bd; user-select:none; min-height:38px;">
                                        <span id="tr-user-display">-- Select Recipient --</span>
                                        <span id="tr-user-arrow" style="transition:transform .2s;">&#9660;</span>
                                    </div>
                                    <div id="tr-user-panel" style="
                                        display:none; position:absolute; z-index:9999; width:100%;
                                        background:#343a40; border:1px solid #6c757d; border-top:none;
                                        border-radius:0 0 4px 4px; max-height:260px; flex-direction:column;">
                                        <div style="padding:8px;">
                                            <input type="text" id="tr-user-search" placeholder="Search username..."
                                                style="width:100%; padding:6px 10px; border:1px solid #6c757d;
                                                       border-radius:4px; background:#495057; color:#fff; outline:none;"
                                                autocomplete="off">
                                        </div>
                                        <ul id="tr-user-list" style="
                                            list-style:none; margin:0; padding:0 0 6px 0;
                                            overflow-y:auto; max-height:195px;">
                                            <?php foreach ($users as $u): if ($u['id'] == $currentUserId) continue; ?>
                                                <li data-value="<?= esc($u['name']) ?>" style="padding:8px 14px; color:#fff; cursor:pointer;">
                                                    <?= esc($u['name']) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Transfer Now</button>
                            <a href="<?= base_url('wallet') ?>" class="btn btn-default">Cancel</a>
                        </div>
                    </form>
                </div>

            </div>
        </section>
    </div>



<script>
(function() {
    var trigger = document.getElementById('tr-user-trigger');
    var panel   = document.getElementById('tr-user-panel');
    var arrow   = document.getElementById('tr-user-arrow');
    var search  = document.getElementById('tr-user-search');
    var list    = document.getElementById('tr-user-list');
    var display = document.getElementById('tr-user-display');
    var hidden  = document.getElementById('receiverName');
    var items   = list.querySelectorAll('li');
    var open    = false;

    items.forEach(function(li) {
        li.addEventListener('mouseenter', function() { li.style.background = '#495057'; });
        li.addEventListener('mouseleave', function() { li.style.background = 'transparent'; });
    });

    trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        open = !open;
        panel.style.display = open ? 'flex' : 'none';
        arrow.style.transform = open ? 'rotate(180deg)' : 'rotate(0deg)';
        if (open) { search.focus(); }
    });

    search.addEventListener('input', function() {
        var filter = this.value.toLowerCase();
        items.forEach(function(li) {
            li.style.display = li.textContent.trim().toLowerCase().indexOf(filter) !== -1 ? '' : 'none';
        });
    });

    items.forEach(function(li) {
        li.addEventListener('click', function() {
            var val = li.getAttribute('data-value');
            display.textContent = val;
            display.style.color = '#fff';
            hidden.value = val;
            open = false;
            panel.style.display = 'none';
            arrow.style.transform = 'rotate(0deg)';
            search.value = '';
            items.forEach(function(x) { x.style.display = ''; });
        });
    });

    document.addEventListener('click', function() {
        if (open) {
            open = false;
            panel.style.display = 'none';
            arrow.style.transform = 'rotate(0deg)';
        }
    });

    panel.addEventListener('click', function(e) { e.stopPropagation(); });
})();
</script>
<?php require_once APPPATH . 'Views/layouts/admin_footer.php'; ?>
