<?php
use Bootstrap\Session;

ob_start();
$authUser = Session::get('auth_user');
$name = is_array($authUser) && is_string($authUser['name'] ?? null) ? (string)$authUser['name'] : 'User';
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Dashboard</h1>
    <ol class="breadcrumb">
      <li><a href="/home"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Welcome</h3>
          </div>
          <div class="box-body">
            <p>Hi, <strong><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></strong>.</p>
            <a class="btn btn-primary btn-sm" href="/users">Users Management</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  $('#dashboard').addClass('active');
</script>

<?php
$content = ob_get_clean();
$title = 'Dashboard';
require BASE_PATH . '/app/views/layouts/main.php';
?>

