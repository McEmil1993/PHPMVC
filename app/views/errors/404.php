<?php
/** @var string $path */

ob_start();
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Not Found</h1>
    <ol class="breadcrumb">
      <li><a href="/home"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">404</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            <p>The page you are looking for does not exist.</p>
            <p><strong>Path:</strong> <code><?= htmlspecialchars((string)($path ?? ''), ENT_QUOTES, 'UTF-8') ?></code></p>
            <a class="btn btn-primary btn-sm" href="/home">Go to Dashboard</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php
$content = ob_get_clean();
$title = '404 Not Found';
require BASE_PATH . '/app/views/layouts/main.php';
?>

