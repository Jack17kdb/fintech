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
                        <a href="<?= base_url('admin/users') ?>" class="nav-link active">
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
                        <h1>User Management</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Registered Users</h3>
                    </div>
                    <div class="card-body">
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

                        <?php if (!empty($users)): ?>
                        <div class="table-responsive"><table id="usersTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Registered</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= esc($user['name']) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td><?= esc($user['location'] ?? 'Not Set') ?></td>
                                        <td>
                                            <?php if($user['status'] == 'active'): ?>
                                                <span class="badge badge-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Blocked</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('Y-m-d H:i', strtotime($user['created_at'])) ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger delete-user" data-id="<?= $user['id'] ?>" data-name="<?= esc($user['name']) ?>">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table></div>

                        <?php if (isset($pager)): ?>
                            <div class="mt-3">
                                <?= $pager->links() ?>
                            </div>
                        <?php endif; ?>

                        <?php else: ?>
                            <div class="alert alert-info">No users registered yet.</div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </section>
    </div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete user <strong id="userName"></strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> This action cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Delete User</a>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#usersTable').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "pageLength": 25,
        "order": [[5, "desc"]]
    });

    $('.delete-user').click(function() {
        var userId = $(this).data('id');
        var userName = $(this).data('name');
        
        $('#userName').text(userName);
        $('#confirmDelete').attr('href', '<?= base_url('admin/users/delete/') ?>' + userId);
        $('#deleteModal').modal('show');
    });
});
</script>

<?php require_once APPPATH . 'Views/layouts/admin_footer.php'; ?>
