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
                    <a href="#" class="d-block"><?= esc(session()->get('user_name') ?? 'Admin') ?></a>
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
                        <a href="<?= base_url('wallet/transfer') ?>" class="nav-link">
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

                    <li class="nav-header">ADMINISTRATION</li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/users') ?>" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Manage Users</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/feerules') ?>" class="nav-link active">
                            <i class="nav-icon fas fa-dollar-sign"></i>
                            <p>Fee Rules</p>
                        </a>
                    </li>
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
                        <h1>Fee Rules Management</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Add New Fee Rule</h3>
                            </div>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger m-3">
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success m-3">
                                    <?= session()->getFlashdata('success') ?>
                                </div>
                            <?php endif; ?>

                            <form action="<?= base_url('admin/feerules') ?>" method="POST">
                            	<?= csrf_field() ?>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Location</label>
                                        <!-- Hidden real select that holds the value for form submission -->
                                        <select name="location" id="location" style="display:none;" required>
                                            <option value=""></option>
                                            <?php foreach ($kenya_locations as $loc): ?>
                                                <option value="<?= esc($loc) ?>"><?= esc($loc) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <!-- Custom dropdown trigger -->
                                        <div id="loc-dropdown" style="position:relative;">
                                            <div id="loc-trigger" style="
                                                display:flex; align-items:center; justify-content:space-between;
                                                padding:8px 12px; background:#343a40; border:1px solid #6c757d;
                                                border-radius:4px; cursor:pointer; color:#fff; user-select:none;">
                                                <span id="loc-display">-- Select Location --</span>
                                                <span id="loc-arrow" style="transition:transform .2s;">&#9660;</span>
                                            </div>
                                            <div id="loc-panel" style="
                                                display:none; position:absolute; z-index:9999; width:100%;
                                                background:#343a40; border:1px solid #6c757d; border-top:none;
                                                border-radius:0 0 4px 4px; max-height:260px; flex-direction:column;">
                                                <div style="padding:8px;">
                                                    <input type="text" id="loc-search" placeholder="Search location..."
                                                        style="width:100%; padding:6px 10px; border:1px solid #6c757d;
                                                               border-radius:4px; background:#495057; color:#fff;"
                                                        autocomplete="off">
                                                </div>
                                                <ul id="loc-list" style="
                                                    list-style:none; margin:0; padding:0 0 6px 0;
                                                    overflow-y:auto; max-height:195px;">
                                                    <?php foreach ($kenya_locations as $loc): ?>
                                                        <li data-value="<?= esc($loc) ?>" style="
                                                            padding:8px 14px; color:#fff; cursor:pointer;">
                                                            <?= esc($loc) ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="transaction_type">Transaction Type</label>
                                        <select name="transaction_type" id="transaction_type" class="form-control" required>
                                            <option value="">-- Select Type --</option>
                                            <option value="wallet_deposit">Deposit</option>
                                            <option value="wallet_transfer">Transfer</option>
                                            <option value="wallet_withdrawal">Withdrawal</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="fixed_fee">Fixed Fee (KSH)</label>
                                        <input type="number" name="fixed_fee" class="form-control" id="fixed_fee" placeholder="0.00" step="0.01" min="0" required>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary btn-block">Add Fee Rule</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Existing Fee Rules</h3>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($rules)): ?>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Transaction Type</th>
                                            <th>Location</th>
                                            <th>Fixed Fee (KSH)</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; foreach ($rules as $rule): ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td>
                                                    <?php
                                                        $label = match($rule['transaction_type']) {
                                                            'wallet_deposit' => 'Deposit',
                                                            'wallet_transfer' => 'Transfer',
                                                            'wallet_withdrawal' => 'Withdrawal',
                                                            default => ucwords(str_replace('_', ' ', $rule['transaction_type']))
                                                        };
                                                        echo $label;
                                                    ?>
                                                </td>
                                                <td><?= esc($rule['location']) ?></td>
                                                <td><strong>KSH <?= number_format($rule['fixed_fee'], 2) ?></strong></td>
                                                <td><?= date('Y-m-d H:i', strtotime($rule['created_at'] ?? 'now')) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <?php if (isset($pager)): ?>
                                    <div class="mt-3">
                                        <?= $pager->links() ?>
                                    </div>
                                <?php endif; ?>

                                <?php else: ?>
                                    <div class="alert alert-info">No fee rules configured yet.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>


<script>
(function() {
    var trigger  = document.getElementById('loc-trigger');
    var panel    = document.getElementById('loc-panel');
    var arrow    = document.getElementById('loc-arrow');
    var search   = document.getElementById('loc-search');
    var list     = document.getElementById('loc-list');
    var display  = document.getElementById('loc-display');
    var hidden   = document.getElementById('location');
    var items    = list.querySelectorAll('li');
    var open     = false;

    // Hover styles
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
            hidden.value = val;
            open = false;
            panel.style.display = 'none';
            arrow.style.transform = 'rotate(0deg)';
            // reset search
            search.value = '';
            items.forEach(function(x) { x.style.display = ''; });
        });
    });

    // Close when clicking outside
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
