<?php
session_start();
require_once __DIR__ . '/php/auth.php';
require_once __DIR__ . '/php/functions.php';
$loggedIn = isLoggedIn();
$user = $loggedIn ? getCurrentUser() : null;

$msg = ''; $msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        $msg = 'Completează toate câmpurile obligatorii.'; $msgType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 'Adresa de email nu este validă.'; $msgType = 'error';
    } else {
        saveContact(['name'=>$name,'email'=>$email,'message'=>$message]);
        $msg = 'Mesajul a fost trimis cu succes! Vă vom contacta în cel mai scurt timp.';
        $msgType = 'success';
    }
}
?>
<!DOCTYPE html>
<html lang="ro" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact — Grand Hotel</title>
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
    <li><a href="contact.php" class="active" data-i18n="nav_contact">Contact</a></li>
    <?php if ($loggedIn): ?>
      <li><a href="dashboard.php" data-i18n="nav_dashboard">Rezervările mele</a></li>
      <li><a href="logout.php" data-i18n="nav_logout">Deconectare</a></li>
    <?php else: ?>
      <li><a href="login.php" data-i18n="nav_login">Login</a></li>
      <li><a href="register.php" data-i18n="nav_register">Register</a></li>
    <?php endif; ?>
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

<!-- HERO mic -->
<div style="background:linear-gradient(135deg,#0d0b09,#1a1510);padding:5rem 2rem 4rem;text-align:center;border-bottom:1px solid rgba(200,168,76,0.2);">
  <p style="font-size:0.6rem;letter-spacing:5px;text-transform:uppercase;color:#c9a84c;margin-bottom:0.75rem;" data-i18n="nav_contact">Contact</p>
  <h1 style="font-family:Georgia,serif;font-size:clamp(2rem,5vw,3.5rem);font-weight:300;color:#e8e0d0;" data-i18n="contact_title">Contactează-ne</h1>
  <div style="width:60px;height:1px;background:linear-gradient(90deg,transparent,#c9a84c,transparent);margin:1.25rem auto;"></div>
  <p style="font-size:0.75rem;letter-spacing:2px;color:rgba(232,224,208,0.5);" data-i18n="contact_sub">Suntem aici pentru tine</p>
</div>

<section>
  <div class="contact-grid">
    <!-- INFO -->
    <div class="contact-info">
      <h3>Grand Hotel Collection</h3>
      <div class="gold-line gold-line-left"></div>
      <p>Echipa noastră este disponibilă pentru a răspunde oricăror întrebări și pentru a vă ajuta să planificați sejurul perfect.</p>

      <div class="contact-detail">
        <div class="icon">📍</div>
        <div class="info">
          <strong>Adresă</strong>
          Str. Elegantei nr. 1, Centru<br>Chișinău, MD-2001
        </div>
      </div>
      <div class="contact-detail">
        <div class="icon">📞</div>
        <div class="info">
          <strong>Telefon</strong>
          +373 22 000 111<br>+373 69 000 222
        </div>
      </div>
      <div class="contact-detail">
        <div class="icon">✉️</div>
        <div class="info">
          <strong>Email</strong>
          reservations@grandhotel.md<br>info@grandhotel.md
        </div>
      </div>
      <div class="contact-detail">
        <div class="icon">🕐</div>
        <div class="info">
          <strong>Program recepție</strong>
          24/7 — Non-stop
        </div>
      </div>
    </div>

    <!-- FORM -->
    <div class="card">
      <?php if ($msg): ?>
        <div class="msg msg-<?= $msgType ?>"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>

      <div class="form-header" style="text-align:left;margin-bottom:1.75rem;">
        <h2 style="font-size:1.6rem;" data-i18n="contact_title">Contactează-ne</h2>
        <p data-i18n="contact_sub">Suntem aici pentru tine</p>
      </div>

      <form id="contactForm" method="POST" action="contact.php" novalidate>
        <div class="form-group">
          <label data-i18n="contact_name">Numele tău</label>
          <input type="text" name="name"
                 value="<?= htmlspecialchars($_POST['name'] ?? ($user['username'] ?? '')) ?>"
                 data-i18n-placeholder="contact_name" placeholder="Numele tău" required>
        </div>
        <div class="form-group">
          <label data-i18n="contact_email">Email</label>
          <input type="email" name="email"
                 value="<?= htmlspecialchars($_POST['email'] ?? ($user['email'] ?? '')) ?>"
                 data-i18n-placeholder="contact_email" placeholder="Email" required>
        </div>
        <div class="form-group">
          <label data-i18n="contact_msg">Mesajul tău</label>
          <textarea name="message" rows="5"
                    data-i18n-placeholder="contact_msg" placeholder="Mesajul tău" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-gold" data-i18n="btn_send">Trimite mesajul</button>
        </div>
      </form>
    </div>
  </div>
</section>

<footer>
  <div class="footer-brand">Grand Hotel</div>
  <div class="gold-line"></div>
  <p data-i18n="footer_copy">© 2025 Grand Hotel — Toate drepturile rezervate</p>
</footer>

<script src="js/script.js"></script>
</body>
</html>