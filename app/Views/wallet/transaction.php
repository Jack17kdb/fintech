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
                        <h1>Transaction Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-right">
                            <button onclick="window.print()" class="btn btn-info btn-sm">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <?php
                    $isCredit = $entry_type === 'credit';
                    $sign     = $isCredit ? '+' : '-';
                    $color    = $isCredit ? 'success' : 'danger';

                    $label = match($transaction['type']) {
                        'wallet_deposit'    => 'Deposit',
                        'wallet_transfer'   => $isCredit ? 'Money Received' : 'Money Sent',
                        'wallet_withdrawal' => 'Withdrawal',
                        default             => ucwords(str_replace('_', ' ', $transaction['type']))
                    };
                ?>

                <div class="card card-<?= $color ?> card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-receipt"></i> Transaction Receipt</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive"><table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="30%">Transaction Type</th>
                                    <td><span class="badge badge-<?= $color ?>"><?= $label ?></span></td>
                                </tr>
                                <tr>
                                    <th>Reference Number</th>
                                    <td><?= esc($transaction['reference']) ?></td>
                                </tr>
                                <tr>
                                    <th>Date & Time</th>
                                    <td><?= date('d/m/Y h:i A', strtotime($transaction['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Account Holder</th>
                                    <td><?= esc($owner['name']) ?> (<?= esc($owner['email']) ?>)</td>
                                </tr>
                                <?php if($transaction['type'] === 'wallet_transfer' && $other_party): ?>
                                <tr>
                                    <th><?= $isCredit ? 'Sender' : 'Recipient' ?></th>
                                    <td><?= esc($other_party['name']) ?> (<?= esc($other_party['email']) ?>)</td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th>Amount</th>
                                    <td class="text-<?= $color ?>"><strong style="font-size: 18px;"><?= $sign ?>KSH <?= number_format($transaction['amount'], 2) ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Transaction Fee</th>
                                    <td>KSH <?= number_format($transaction['fee_amount'], 2) ?></td>
                                </tr>
                                <tr>
                                    <th>Total <?= $isCredit ? 'Received' : 'Deducted' ?></th>
                                    <td class="text-<?= $color ?>"><strong style="font-size: 18px;"><?= $sign ?>KSH <?= number_format($entry_amount, 2) ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="badge badge-success"><?= strtoupper(esc($transaction['status'])) ?></span></td>
                                </tr>
                            </tbody>
                        </table></div>

                        <div class="row mt-4 no-print">
                            <div class="col-md-6">
                                <a href="<?= base_url('wallet/transactions') ?>" class="btn btn-primary btn-block">
                                    <i class="fas fa-arrow-left"></i> Back to Transactions
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="<?= base_url('wallet') ?>" class="btn btn-default btn-block">
                                    <i class="fas fa-home"></i> Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

<style>
@media print {
    .main-sidebar, .main-header, .no-print {
        display: none !important;
    }
    .content-wrapper {
        margin-left: 0 !important;
    }
}
</style>

<?php require_once APPPATH . 'Views/layouts/admin_footer.php'; ?>
