<?php
/** @var string|null $message */
/** @var array<string,string> $errors */
/** @var array<string,mixed> $old */

$errors = $errors ?? [];
$old = $old ?? [];
$emailOld = is_string($old['email'] ?? null) ? (string)$old['email'] : '';
$emailInvalid = array_key_exists('email', $errors);
$passwordInvalid = array_key_exists('password', $errors);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>

    <link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/plugins/dist/css/AdminLTE.min.css" />
    <link rel="stylesheet" href="/plugins/iCheck/square/blue.css" />
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="/"><b>App</b> Login</a>
      </div>

      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <?php if (!empty($message)): ?>
          <div class="alert alert-danger" role="alert"><?= htmlspecialchars((string)$message, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form action="/login" method="post">
          <div class="form-group has-feedback <?= $emailInvalid ? 'has-error' : '' ?>">
            <input
              type="email"
              name="email"
              class="form-control<?= $emailInvalid ? ' invalid' : '' ?>"
              placeholder="Email"
              value="<?= htmlspecialchars($emailOld, ENT_QUOTES, 'UTF-8') ?>"
              required
              <?= $emailInvalid ? 'aria-invalid="true"' : '' ?>
            />
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            <?php if ($emailInvalid): ?>
              <span class="help-block"><?= htmlspecialchars((string)$errors['email'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
          </div>
          <div class="form-group <?= $passwordInvalid ? 'has-error' : '' ?>">
            <div class="input-group">
              <input
                id="password"
                type="password"
                name="password"
                class="form-control<?= $passwordInvalid ? ' invalid' : '' ?>"
                placeholder="Password"
                required
                <?= $passwordInvalid ? 'aria-invalid="true"' : '' ?>
              />
              <span class="input-group-btn">
                <button id="toggle-password" class="btn btn-default" type="button" aria-label="Show password">
                  <span id="toggle-password-icon" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                </button>
              </span>
            </div>
            <?php if ($passwordInvalid): ?>
              <span class="help-block"><?= htmlspecialchars((string)$errors['password'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember" value="1" /> Remember me
                </label>
              </div>
            </div>
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%'
        });

        $('#toggle-password').on('click', function () {
          var input = document.getElementById('password');
          var icon = document.getElementById('toggle-password-icon');
          if (!input || !icon) return;

          var isHidden = input.type === 'password';
          input.type = isHidden ? 'text' : 'password';
          icon.className = isHidden ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open';
          this.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });
      });
    </script>
  </body>
</html>
