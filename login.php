<?php
require_once __DIR__ . '/config/auth.php';
ensure_default_admin();
if (is_logged_in()) { header('Location: ' . BASE_URL . '/admin/dashboard.php'); exit; }

$error = '';
$locked = ($_SESSION['lock_until'] ?? 0) > time();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($locked) {
        $error = 'Too many failed attempts. Try again in a few minutes.';
    } elseif (!csrf_valid()) {
        $error = 'Your session expired. Please try again.';
    } else {
        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        if ($u === '' || $p === '') {
            $error = 'Enter your username and password.';
        } elseif (login_admin($u, $p)) {
            unset($_SESSION['attempts'], $_SESSION['lock_until']);
            header('Location: ' . BASE_URL . '/admin/dashboard.php'); exit;
        } else {
            $_SESSION['attempts'] = ($_SESSION['attempts'] ?? 0) + 1;
            if ($_SESSION['attempts'] >= MAX_ATTEMPTS) { $_SESSION['lock_until'] = time() + LOCK_SECONDS; $_SESSION['attempts'] = 0; }
            $error = 'Incorrect username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sign in | KLE SOCIETY'S DEGREE COLLEGE ANKALI</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="login-wrap">
  <section class="login-brand">
    <i class="fa-solid fa-graduation-cap crest"></i>
    <h1>KLE SOCIETY'S DEGREE COLLEGE ANKALI</h1>
    <p>Smart Student &amp; Faculty Attendance Management System. Classroom attendance, verified and recorded in one place.</p>
  </section>
  <section class="login-form-col">
    <div class="login-card">
      <h2 class="h4 fw-bold mb-1" style="color:var(--primary)">Administrator sign in</h2>
      <p class="text-muted small mb-4">Authorised college staff only.</p>
      <?php if ($error): ?><div class="alert alert-danger py-2" role="alert"><?= e($error) ?></div><?php endif; ?>
      <form method="post" autocomplete="off" novalidate>
        <?= csrf_field() ?>
        <div class="mb-3"><label class="form-label" for="username">Username</label>
          <div class="input-group"><span class="input-group-text"><i class="fa-solid fa-user"></i></span>
          <input class="form-control" id="username" name="username" required autofocus maxlength="50"></div></div>
        <div class="mb-3"><label class="form-label" for="password">Password</label>
          <div class="input-group"><span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
          <input class="form-control" type="password" id="password" name="password" required></div></div>
        <button class="btn btn-primary w-100" type="submit" <?= $locked ? 'disabled' : '' ?>>Sign in</button>
      </form>
      <div class="login-footer text-center">Developed &amp; Designed by Vishwa</div>
    </div>
  </section>
</div>
</body></html>
