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
                <!-- Hidden select for form submission -->
                <select name="location" id="reg-location" style="display:none;" required>
                    <option value=""></option>
                    <?php
                    $kenya_locations = ['Baringo','Bomet','Bungoma','Busia','Elgeyo-Marakwet','Embu','Garissa','Homa Bay','Isiolo','Kajiado','Kakamega','Kericho','Kiambu','Kilifi','Kirinyaga','Kisii','Kisumu','Kitui','Kwale','Laikipia','Lamu','Machakos','Makueni','Mandera','Marsabit','Meru','Migori','Mombasa',"Murang'a",'Nairobi','Nakuru','Nandi','Narok','Nyamira','Nyandarua','Nyeri','Samburu','Siaya','Taita-Taveta','Tana River','Tharaka-Nithi','Trans-Nzoia','Turkana','Uasin Gishu','Vihiga','Wajir','West Pokot'];
                    sort($kenya_locations);
                    foreach($kenya_locations as $loc): ?>
                        <option value="<?= esc($loc) ?>"><?= esc($loc) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="input-group mb-3" style="position:relative; display:block;">
                    <!-- Trigger bar styled to match other input-groups -->
                    <div id="reg-loc-trigger" style="
                        display:flex; align-items:center; justify-content:space-between;
                        padding:8px 12px; background:#343a40; border:1px solid #6c757d;
                        border-radius:4px; cursor:pointer; color:#adb5bd; user-select:none; height:38px;">
                        <span id="reg-loc-display" style="display:flex;align-items:center;gap:8px;">
                            <i class="fas fa-map-marker-alt" style="color:#6c757d;"></i>
                            <span id="reg-loc-text">Select Location</span>
                        </span>
                        <span id="reg-loc-arrow" style="transition:transform .2s; color:#6c757d;">&#9660;</span>
                    </div>
                    <!-- Dropdown panel -->
                    <div id="reg-loc-panel" style="
                        display:none; position:absolute; z-index:9999; width:100%;
                        background:#343a40; border:1px solid #6c757d; border-top:none;
                        border-radius:0 0 4px 4px; max-height:260px; flex-direction:column;">
                        <div style="padding:8px;">
                            <input type="text" id="reg-loc-search" placeholder="Search location..."
                                style="width:100%; padding:6px 10px; border:1px solid #6c757d;
                                       border-radius:4px; background:#495057; color:#fff; outline:none;"
                                autocomplete="off">
                        </div>
                        <ul id="reg-loc-list" style="
                            list-style:none; margin:0; padding:0 0 6px 0;
                            overflow-y:auto; max-height:185px;">
                            <?php foreach($kenya_locations as $loc): ?>
                                <li data-value="<?= esc($loc) ?>" style="padding:8px 14px; color:#fff; cursor:pointer;">
                                    <?= esc($loc) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
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

<script>
(function() {
    var trigger = document.getElementById('reg-loc-trigger');
    var panel   = document.getElementById('reg-loc-panel');
    var arrow   = document.getElementById('reg-loc-arrow');
    var search  = document.getElementById('reg-loc-search');
    var list    = document.getElementById('reg-loc-list');
    var text    = document.getElementById('reg-loc-text');
    var hidden  = document.getElementById('reg-location');
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
            text.textContent = val;
            text.style.color = '#fff';
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
</body>
</html>
