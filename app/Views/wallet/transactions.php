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
                        <a href="<?= base_url('wallet/transactions') ?>" class="nav-link active">
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
                        <h1>Transaction History</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Filter Transactions</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('wallet/transactions') ?>" method="GET" id="filterForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date From</label>
                                        <input type="date" name="date_from" class="form-control" value="<?= esc($date_from) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date To</label>
                                        <input type="date" name="date_to" class="form-control" value="<?= esc($date_to) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Search Keyword</label>
                                        <input type="text" name="keyword" class="form-control" placeholder="Search by reference, type, email..." value="<?= esc($keyword ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <div class="text-muted mb-3">
                            <?php if($date_from === $date_to): ?>
                                Showing transactions for <strong><?= date('F d, Y', strtotime($date_from)) ?></strong>
                            <?php else: ?>
                                Showing transactions from <strong><?= date('F d, Y', strtotime($date_from)) ?></strong> to <strong><?= date('F d, Y', strtotime($date_to)) ?></strong>
                            <?php endif; ?>
                            <?php if(!empty($keyword)): ?>
                                | Keyword: <strong><?= esc($keyword) ?></strong>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of Transactions</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm btn-success" onclick="exportToExcel()">
                                <i class="fas fa-file-excel"></i> Export CSV
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </button>
                            <button type="button" class="btn btn-sm btn-info" onclick="printTable()">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($transactions)): ?>
                        <table id="transactionTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Reference</th>
                                    <th>Party</th>
                                    <th>Amount</th>
                                    <th>Fee</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach ($transactions as $t): ?>
                                    <?php
                                        $isCredit = $t['entry_type'] === 'credit';
                                        $sign     = $isCredit ? '+' : '-';
                                        $class    = $isCredit ? 'text-success' : 'text-danger';

                                        $label = match($t['type']) {
                                            'wallet_deposit'    => 'Deposit',
                                            'wallet_transfer'   => $isCredit ? 'Transfer Received' : 'Transfer Sent',
                                            'wallet_withdrawal' => 'Withdrawal',
                                            default             => ucwords(str_replace('_', ' ', $t['type']))
                                        };
                                    ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= date('Y-m-d H:i', strtotime($t['created_at'])) ?></td>
                                        <td><?= $label ?></td>
                                        <td><?= esc($t['reference']) ?></td>
                                        <td><?= esc($t['party_name'] ?? $username) ?></td>
                                        <td class="<?= $class ?>"><strong><?= $sign ?>KSH <?= number_format($t['entry_amount'], 2) ?></strong></td>
                                        <td>KSH <?= number_format($t['fee_amount'], 2) ?></td>
                                        <td><span class="badge badge-success"><?= esc($t['status']) ?></span></td>
                                        <td>
                                            <a href="<?= base_url('wallet/transaction/' . $t['transaction_id']) ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                            <div class="alert alert-info">No transactions found for the selected filters.</div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </section>
    </div>

<script>
$(document).ready(function() {
    $('#transactionTable').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "pageLength": 25,
        "order": [[1, "desc"]]
    });
});

function exportToExcel() {
    var params = new URLSearchParams(window.location.search);
    window.location.href = '<?= base_url('wallet/transactions/export-csv') ?>?' + params.toString();
}

function exportToPDF() {
    var params = new URLSearchParams(window.location.search);
    window.location.href = '<?= base_url('wallet/transactions/export-pdf') ?>?' + params.toString();
}

function printTable() {
    window.print();
}
</script>

<style>
@media print {
    .main-sidebar, .main-header, .card-tools, .btn, .pagination, .no-print {
        display: none !important;
    }
    .content-wrapper {
        margin-left: 0 !important;
    }
}
</style>

<?php require_once APPPATH . 'Views/layouts/admin_footer.php'; ?>
