<?php
session_start();
require_once __DIR__ . '/php/auth.php';

if (isLoggedIn()) { header('Location: dashboard.php'); exit; }

$error = '';
$success = '';

$msgCode = $_GET['msg'] ?? '';
if ($msgCode === 'nologin')    $error   = 'Trebuie să fii autentificat pentru a accesa această pagină.';
if ($msgCode === 'loggedout')  $success = 'Ai fost deconectat cu succes.';
if ($msgCode === 'registered') $success = 'Cont creat cu succes! Te poți autentifica acum.';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Completează toate câmpurile.';
    } else {
        $result = loginUser($email, $password);
        if ($result['success']) {
            $_SESSION['user_id']  = $result['user']['id'];
            $_SESSION['username'] = $result['user']['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Grand Hotel</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
  <a href="index.php" class="nav-brand">Grand Hotel <span>Collection</span></a>
  <button class="hamburger" id="hamburger"><span></span><span></span><span></span></button>
  <ul class="nav-links" id="navLinks">
    <li><a href="index.php" data-i18n="nav_home">Acasă</a></li>
    <li><a href="about.php" data-i18n="nav_about">Despre</a></li>
    <li><a href="index.php#rooms" data-i18n="nav_rooms">Camere</a></li>
    <li><a href="contact.php" data-i18n="nav_contact">Contact</a></li>
    <li><a href="login.php" class="active" data-i18n="nav_login">Login</a></li>
    <li><a href="register.php" data-i18n="nav_register">Register</a></li>
  </ul>
  <div class="nav-controls">
    <select id="langSelect" class="lang-select">
      <option value="ro">🇷🇴 RO</option>
      <option value="en">🇬🇧 EN</option>
      <option value="ru">🇷🇺 RU</option>
    </select>
    <button id="themeToggle" class="theme-toggle">◑ Dark</button>
  </div>
</nav>

<main>
  <div class="form-container card" style="margin-top:3rem;">
    <div class="form-header">
      <h2 data-i18n="btn_login">Autentificare</h2>
      <p data-i18n="hero_tag">Grand Hotel Collection</p>
    </div>
    <div class="gold-line"></div>

    <?php if ($error): ?>
      <div class="msg msg-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="msg msg-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form id="loginForm" method="POST" action="login.php" novalidate>
      <div class="form-group">
        <label data-i18n="form_email">Email</label>
        <input type="email" name="email"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               data-i18n-placeholder="form_email" placeholder="Email" required>
      </div>
      <div class="form-group">
        <label data-i18n="form_pass">Parolă</label>
        <input type="password" name="password"
               data-i18n-placeholder="form_pass" placeholder="Parolă" required>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-gold" data-i18n="btn_login">Autentificare</button>
      </div>
    </form>

    <p class="form-footer">
      Nu ai cont? <a href="register.php" data-i18n="btn_register">Creare cont</a>
    </p>
  </div>
</main>

<footer>
  <div class="footer-brand">Grand Hotel</div>
  <div class="gold-line"></div>
  <p data-i18n="footer_copy">© 2025 Grand Hotel — Toate drepturile rezervate</p>
</footer>

<script src="js/script.js"></script>
</body>
</html>