<?php
session_start();
require_once __DIR__ . '/php/auth.php';
$loggedIn = isLoggedIn();
$user = $loggedIn ? getCurrentUser() : null;
?>
<!DOCTYPE html>
<html lang="ro" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Despre noi — Grand Hotel</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
  <a href="index.php" class="nav-brand">Grand Hotel <span>Collection</span></a>
  <button class="hamburger" id="hamburger"><span></span><span></span><span></span></button>
  <ul class="nav-links" id="navLinks">
    <li><a href="index.php" data-i18n="nav_home">Acasă</a></li>
    <li><a href="about.php" class="active" data-i18n="nav_about">Despre</a></li>
    <li><a href="index.php#rooms" data-i18n="nav_rooms">Camere</a></li>
    <li><a href="contact.php" data-i18n="nav_contact">Contact</a></li>
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
<div style="background: linear-gradient(135deg, #0d0b09, #1a1510); padding: 5rem 2rem 4rem; text-align:center; border-bottom: 1px solid rgba(200,168,76,0.2);">
  <p style="font-size:0.6rem; letter-spacing:5px; text-transform:uppercase; color: #c9a84c; margin-bottom:0.75rem;" data-i18n="nav_about">Despre</p>
  <h1 style="font-family: Georgia, serif; font-size: clamp(2rem,5vw,3.5rem); font-weight:300; color:#e8e0d0;" data-i18n="about_title">O poveste de eleganță</h1>
  <div style="width:60px;height:1px;background:linear-gradient(90deg,transparent,#c9a84c,transparent);margin:1.25rem auto;"></div>
</div>

<section>
  <div class="about-grid">
    <div class="about-text">
      <p style="font-size:0.6rem;letter-spacing:4px;text-transform:uppercase;color:var(--gold);margin-bottom:0.75rem;" data-i18n="nav_about">Despre noi</p>
      <h2 data-i18n="about_title">O poveste de eleganță</h2>
      <div class="gold-line gold-line-left"></div>
      <p data-i18n="about_p1">Fondat în inima orașului, Grand Hotel reprezintă vârful ospitalității de lux. Cu peste 30 de ani de tradiție, oferim oaspeților noștri experiențe memorabile.</p>
      <p data-i18n="about_p2">Fiecare detaliu a fost gândit cu grijă — de la selecția materialelor premium până la formarea echipei noastre dedicate. Scopul nostru este să transformăm fiecare sejur într-o amintire de neuitat.</p>
      <p style="font-size:0.82rem;color:var(--text-light);line-height:1.9;">Suntem mândri să fim recunoscuți cu premiul <strong style="color:var(--gold);">Five Star Hospitality Award</strong> pentru al cincilea an consecutiv, confirmând angajamentul nostru față de excelență.</p>
      <div style="margin-top:2rem;">
        <a href="index.php#rooms" class="btn btn-outline-gold" data-i18n="btn_discover">Descoperă camerele</a>
      </div>
    </div>
    <div class="about-visual">🏨</div>
  </div>
</section>

<!-- VALORI -->
<section style="background: var(--bg-section);">
  <p class="section-label">Valorile noastre</p>
  <div class="gold-line"></div>
  <h2 class="section-title">Ce ne definește</h2>
  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap:2rem; max-width:1000px; margin:2.5rem auto 0;">
    <?php
    $values = [
      ['icon'=>'🤝','title'=>'Integritate','desc'=>'Onestitate și transparență în toate interacțiunile cu oaspeții noștri.'],
      ['icon'=>'💎','title'=>'Excelență','desc'=>'Standarde înalte în fiecare aspect al serviciilor oferite.'],
      ['icon'=>'🌿','title'=>'Sustenabilitate','desc'=>'Angajament față de mediu și comunitatea locală.'],
      ['icon'=>'❤️','title'=>'Pasiune','desc'=>'Dragoste autentică pentru ospitalitate și satisfacția oaspeților.'],
    ];
    foreach ($values as $v): ?>
      <div style="background:var(--bg-card);border:1px solid var(--border);padding:2rem;text-align:center;">
        <div style="font-size:2.5rem;margin-bottom:1rem;"><?= $v['icon'] ?></div>
        <h3 style="font-family:Georgia,serif;font-size:1.2rem;color:var(--gold);margin-bottom:0.5rem;"><?= $v['title'] ?></h3>
        <p style="font-size:0.78rem;color:var(--text-light);line-height:1.7;"><?= $v['desc'] ?></p>
      </div>
    <?php endforeach; ?>
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