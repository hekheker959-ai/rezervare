<?php
// index.php — Pagina principală Grand Hotel
session_start();
require_once __DIR__ . '/php/auth.php';
require_once __DIR__ . '/php/functions.php';
$loggedIn = isLoggedIn();
$rooms = getRooms();
?>
<!DOCTYPE html>
<html lang="ro" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grand Hotel — Lux & Eleganță</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav>
  <a href="index.php" class="nav-brand">
    Grand Hotel
    <span>Collection</span>
  </a>

  <button class="hamburger" id="hamburger" aria-label="Meniu">
    <span></span><span></span><span></span>
  </button>

  <ul class="nav-links" id="navLinks">
    <li><a href="index.php" class="active" data-i18n="nav_home">Acasă</a></li>
    <li><a href="#despre" data-i18n="nav_about">Despre</a></li>
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

<!-- HERO -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-pattern"></div>
  <div class="hero-content">
    <p class="hero-eyebrow" data-i18n="hero_tag">Lux & Eleganță</p>
    <h1>
      <em data-i18n="hero_title1">Grand</em><br>
      <span data-i18n="hero_title2">Hotel</span>
    </h1>
    <p class="hero-subtitle" data-i18n="hero_sub">Experiențe de neuitat în fiecare cameră</p>
    <div class="hero-btns">
      <?php if ($loggedIn): ?>
        <a href="dashboard.php" class="btn btn-gold" data-i18n="btn_reserve">Rezervă acum</a>
      <?php else: ?>
        <a href="register.php" class="btn btn-gold" data-i18n="btn_reserve">Rezervă acum</a>
      <?php endif; ?>
      <a href="#rooms" class="btn btn-outline" data-i18n="btn_discover">Descoperă</a>
    </div>
  </div>
</section>

<!-- FEATURES STRIP -->
<div class="features-strip">
  <p class="section-label" style="margin-bottom:2rem;" data-i18n="feat_label">De ce noi</p>
  <div class="features-strip-grid">
    <div class="strip-item">
      <div class="strip-icon">🌟</div>
      <div class="strip-title" data-i18n="feat1_title">Servicii Premium</div>
      <div class="strip-desc" data-i18n="feat1_desc">Echipă dedicată disponibilă 24/7 pentru orice nevoie.</div>
    </div>
    <div class="strip-item">
      <div class="strip-icon">🏛️</div>
      <div class="strip-title" data-i18n="feat2_title">Design Exclusiv</div>
      <div class="strip-desc" data-i18n="feat2_desc">Interioare concepute de arhitecți de renume internațional.</div>
    </div>
    <div class="strip-item">
      <div class="strip-icon">📍</div>
      <div class="strip-title" data-i18n="feat3_title">Locație Centrală</div>
      <div class="strip-desc" data-i18n="feat3_desc">În inima orașului, la doi pași de atracțiile principale.</div>
    </div>
    <div class="strip-item">
      <div class="strip-icon">🍽️</div>
      <div class="strip-title" data-i18n="feat4_title">Gastronomie Rafinată</div>
      <div class="strip-desc" data-i18n="feat4_desc">Restaurant cu specific local și bucătărie internațională.</div>
    </div>
  </div>
</div>

<!-- DESPRE -->
<section id="despre">
  <div class="about-grid">
    <div class="about-text">
      <p style="font-size:0.6rem;letter-spacing:4px;text-transform:uppercase;color:var(--gold);margin-bottom:0.75rem;" data-i18n="nav_about">Despre noi</p>
      <h2 style="font-family:Georgia,serif;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:300;color:var(--text);margin-bottom:1rem;" data-i18n="about_title">O poveste de eleganță</h2>
      <div class="gold-line gold-line-left"></div>
      <p style="font-size:0.82rem;color:var(--text-light);line-height:1.9;margin-bottom:1rem;" data-i18n="about_p1">Fondat în inima orașului, Grand Hotel reprezintă vârful ospitalității de lux. Cu peste 30 de ani de tradiție, oferim oaspeților noștri experiențe memorabile.</p>
      <p style="font-size:0.82rem;color:var(--text-light);line-height:1.9;margin-bottom:1.5rem;" data-i18n="about_p2">Fiecare detaliu a fost gândit cu grijă — de la selecția materialelor premium până la formarea echipei noastre dedicate.</p>
      <a href="about.php" class="btn btn-outline-gold" data-i18n="btn_discover">Descoperă mai mult</a>
    </div>
    <div class="about-visual">🏨</div>
  </div>
</section>

<!-- ROOMS -->
<section id="rooms" style="background: var(--bg-section);">
  <p class="section-label" data-i18n="rooms_label">Cazare exclusivă</p>
  <div class="gold-line"></div>
  <h2 class="section-title" data-i18n="rooms_title">Camerele noastre</h2>
  <p class="section-sub" data-i18n="rooms_sub">Fiecare cameră este un sanctuar al confortului</p>

  <div class="rooms-grid">
    <?php foreach ($rooms as $room): ?>
    <div class="room-card">
      <div class="room-img">
        <?= $room['icon'] ?>
        <span class="room-badge"><?= htmlspecialchars($room['badge']) ?></span>
      </div>
      <div class="room-body">
        <h3 class="room-name"><?= htmlspecialchars($room['name']) ?></h3>
        <p class="room-desc"><?= htmlspecialchars($room['desc']) ?></p>
        <div class="room-features">
          <?php foreach ($room['features'] as $f): ?>
            <span class="room-feature"><?= htmlspecialchars($f) ?></span>
          <?php endforeach; ?>
        </div>
        <div class="room-price">
          <span class="amount">€<?= $room['price'] ?></span>
          <span class="per">/ noapte</span>
        </div>
        <?php if ($loggedIn): ?>
          <button class="btn btn-outline-gold btn-sm"
            onclick="openReserveModal('<?= $room['id'] ?>', '<?= addslashes($room['name']) ?>', <?= $room['price'] ?>)"
            data-i18n="btn_book">Rezervă</button>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline-gold btn-sm" data-i18n="btn_book">Rezervă</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- RESERVATION MODAL -->
<?php if ($loggedIn): ?>
<div class="modal-overlay" id="reserveModal" onclick="closeIfOverlay(event,'reserveModal')">
  <div class="modal">
    <button class="modal-close" onclick="document.getElementById('reserveModal').classList.remove('open')">✕</button>
    <h3 id="modalRoomName">Rezervare</h3>
    <div class="gold-line"></div>
    <p id="modalRoomPrice" style="font-size:0.75rem;color:var(--gold);letter-spacing:2px;margin-bottom:1.5rem;"></p>

    <form class="reservationForm" method="POST" action="dashboard.php">
      <input type="hidden" name="action" value="reserve">
      <input type="hidden" name="room_id" id="modalRoomId">

      <div class="form-row">
        <div class="form-group">
          <label>Check-in</label>
          <input type="date" name="checkin" required>
        </div>
        <div class="form-group">
          <label>Check-out</label>
          <input type="date" name="checkout" required>
        </div>
      </div>
      <div class="form-group">
        <label>Număr de oaspeți</label>
        <select name="guests">
          <option value="1">1 oaspete</option>
          <option value="2" selected>2 oaspeți</option>
          <option value="3">3 oaspeți</option>
          <option value="4">4 oaspeți</option>
        </select>
      </div>
      <div class="form-group">
        <label>Cereri speciale (opțional)</label>
        <textarea name="requests" placeholder="Ex: pat suplimentar, etaj înalt, vedere la piscină..."></textarea>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-gold">Confirmă rezervarea</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<footer>
  <div class="footer-brand">Grand Hotel</div>
  <div class="gold-line"></div>
  <p data-i18n="footer_copy">© 2025 Grand Hotel — Toate drepturile rezervate</p>
</footer>

<script src="js/script.js"></script>
<script>
function openReserveModal(id, name, price) {
  document.getElementById('modalRoomId').value = id;
  document.getElementById('modalRoomName').textContent = name;
  document.getElementById('modalRoomPrice').textContent = '€' + price + ' / noapte';
  document.getElementById('reserveModal').classList.add('open');
}
</script>
</body>
</html>