<?php
session_start();
require_once __DIR__ . '/php/auth.php';
require_once __DIR__ . '/php/functions.php';

requireLogin();

$user  = getCurrentUser();
$reservations = getUserReservations($_SESSION['user_id']);
$rooms = getRooms();

$msg = ''; $msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'reserve') {
        $result = addReservation($_SESSION['user_id'], $_POST);
        $msg = $result['message'];
        $msgType = $result['success'] ? 'success' : 'error';
        $reservations = getUserReservations($_SESSION['user_id']);

    } elseif ($action === 'cancel') {
        $result = deleteReservation($_POST['res_id'] ?? '', $_SESSION['user_id']);
        $msg = $result['message'];
        $msgType = $result['success'] ? 'success' : 'error';
        $reservations = getUserReservations($_SESSION['user_id']);
    }
}

// Statistici
$totalNights = array_sum(array_column($reservations, 'nights'));
$totalSpent  = array_sum(array_column($reservations, 'total'));
$upcoming    = count(array_filter($reservations, fn($r) => strtotime($r['checkin']) >= strtotime('today') && $r['status'] !== 'cancelled'));
?>
<!DOCTYPE html>
<html lang="ro" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rezervările mele — Grand Hotel</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
  <a href="index.php" class="nav-brand">Grand Hotel <span>Collection</span></a>
  <button class="hamburger" id="hamburger"><span></span><span></span><span></span></button>
  <ul class="nav-links" id="navLinks">
    <li><a href="index.php" data-i18n="nav_home">Acasă</a></li>
    <li><a href="index.php#rooms" data-i18n="nav_rooms">Camere</a></li>
    <li><a href="contact.php" data-i18n="nav_contact">Contact</a></li>
    <li><a href="dashboard.php" class="active" data-i18n="nav_dashboard">Rezervările mele</a></li>
    <li><a href="logout.php" data-i18n="nav_logout">Deconectare</a></li>
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
  <?php if ($msg): ?>
    <div class="msg msg-<?= $msgType ?>" style="margin-bottom:1.5rem;"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <!-- DASHBOARD HERO -->
  <div class="dash-hero">
    <div>
      <h2>Bun venit, <?= htmlspecialchars($user['username'] ?? 'Oaspete') ?></h2>
      <p>Grand Hotel Collection — Panoul tău personal</p>
    </div>
    <button class="btn btn-gold" onclick="document.getElementById('reserveModal').classList.add('open')">
      + Rezervare nouă
    </button>
  </div>

  <!-- STATS -->
  <div class="stats-row">
    <div class="stat-box">
      <div class="stat-num"><?= count($reservations) ?></div>
      <div class="stat-label">Total rezervări</div>
    </div>
    <div class="stat-box">
      <div class="stat-num"><?= $upcoming ?></div>
      <div class="stat-label">Rezervări viitoare</div>
    </div>
    <div class="stat-box">
      <div class="stat-num"><?= $totalNights ?></div>
      <div class="stat-label">Nopți totale</div>
    </div>
    <div class="stat-box">
      <div class="stat-num">€<?= number_format($totalSpent, 0) ?></div>
      <div class="stat-label">Total cheltuit</div>
    </div>
  </div>

  <!-- REZERVĂRI -->
  <div class="card">
    <div class="section-header">
      <h3>Rezervările mele</h3>
      <div style="font-size:0.72rem;color:var(--text-light);">
        <?= count($reservations) ?> rezervare(i) găsită/găsite
      </div>
    </div>

    <?php if (empty($reservations)): ?>
      <div class="empty-state">
        <div class="empty-icon">🏨</div>
        <p>Nu ai nicio rezervare încă.<br>Apasă <strong>+ Rezervare nouă</strong> pentru a rezerva o cameră.</p>
      </div>
    <?php else: ?>
      <!-- Filter -->
      <div class="filter-bar" style="margin-bottom:1.5rem;">
        <input type="text" id="filterRes" placeholder="🔍 Caută cameră..." oninput="filterReservations()">
        <select id="filterStatus" onchange="filterReservations()">
          <option value="">Toate statusurile</option>
          <option value="confirmed">Confirmate</option>
          <option value="cancelled">Anulate</option>
        </select>
      </div>

      <div style="overflow-x:auto;">
        <table class="res-table" id="resTable">
          <thead>
            <tr>
              <th>Cameră</th>
              <th>Check-in</th>
              <th>Check-out</th>
              <th>Nopți</th>
              <th>Oaspeți</th>
              <th>Total</th>
              <th>Status</th>
              <th>Acțiuni</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (array_reverse($reservations) as $res): ?>
            <tr data-room="<?= strtolower(htmlspecialchars($res['room_name'])) ?>"
                data-status="<?= $res['status'] ?>">
              <td><strong><?= htmlspecialchars($res['room_name']) ?></strong></td>
              <td><?= htmlspecialchars($res['checkin']) ?></td>
              <td><?= htmlspecialchars($res['checkout']) ?></td>
              <td><?= intval($res['nights']) ?></td>
              <td><?= intval($res['guests']) ?></td>
              <td><strong style="color:var(--gold);">€<?= number_format($res['total'], 0) ?></strong></td>
              <td>
                <span class="status-badge status-<?= $res['status'] ?>">
                  <?= $res['status'] === 'confirmed' ? 'Confirmată' : 'Anulată' ?>
                </span>
              </td>
              <td>
                <?php if ($res['status'] === 'confirmed'): ?>
                <form method="POST" onsubmit="return confirm('Anulezi această rezervare?')">
                  <input type="hidden" name="action" value="cancel">
                  <input type="hidden" name="res_id" value="<?= $res['id'] ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Anulează</button>
                </form>
                <?php else: ?>
                  <span style="font-size:0.65rem;color:var(--text-light);">—</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</main>

<!-- MODAL REZERVARE -->
<div class="modal-overlay" id="reserveModal" onclick="closeIfOverlay(event,'reserveModal')">
  <div class="modal">
    <button class="modal-close" onclick="document.getElementById('reserveModal').classList.remove('open')">✕</button>
    <h3>Rezervare nouă</h3>
    <div class="gold-line"></div>

    <form class="reservationForm" method="POST" action="dashboard.php">
      <input type="hidden" name="action" value="reserve">

      <div class="form-group">
        <label>Cameră</label>
        <select name="room_id" id="roomSelect" onchange="updatePrice()">
          <?php foreach ($rooms as $r): ?>
            <option value="<?= $r['id'] ?>" data-price="<?= $r['price'] ?>">
              <?= htmlspecialchars($r['name']) ?> — €<?= $r['price'] ?>/noapte
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Check-in</label>
          <input type="date" name="checkin" id="checkinInput" required onchange="calcTotal()">
        </div>
        <div class="form-group">
          <label>Check-out</label>
          <input type="date" name="checkout" id="checkoutInput" required onchange="calcTotal()">
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

      <!-- Preview total -->
      <div id="totalPreview" style="display:none;background:rgba(200,168,76,0.08);border:1px solid var(--border-gold);padding:1rem;margin-bottom:1rem;font-size:0.8rem;">
        <div style="display:flex;justify-content:space-between;">
          <span style="color:var(--text-light);">Total estimat:</span>
          <span id="totalAmount" style="color:var(--gold);font-size:1.1rem;font-family:Georgia,serif;"></span>
        </div>
        <div style="font-size:0.65rem;color:var(--text-light);margin-top:0.25rem;" id="nightsLabel"></div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-gold">Confirmă rezervarea</button>
      </div>
    </form>
  </div>
</div>

<footer>
  <div class="footer-brand">Grand Hotel</div>
  <div class="gold-line"></div>
  <p data-i18n="footer_copy">© 2025 Grand Hotel — Toate drepturile rezervate</p>
</footer>

<script src="js/script.js"></script>
<script>
function closeIfOverlay(e, id) {
  if (e.target === e.currentTarget) document.getElementById(id).classList.remove('open');
}

function calcTotal() {
  const checkin  = document.getElementById('checkinInput').value;
  const checkout = document.getElementById('checkoutInput').value;
  const sel      = document.getElementById('roomSelect');
  const price    = parseInt(sel.options[sel.selectedIndex].dataset.price) || 0;

  if (checkin && checkout) {
    const nights = Math.round((new Date(checkout) - new Date(checkin)) / 86400000);
    if (nights > 0) {
      document.getElementById('totalPreview').style.display = 'block';
      document.getElementById('totalAmount').textContent = '€' + (nights * price).toLocaleString();
      document.getElementById('nightsLabel').textContent = nights + ' noapte(i) × €' + price + '/noapte';
      return;
    }
  }
  document.getElementById('totalPreview').style.display = 'none';
}

function updatePrice() { calcTotal(); }

function filterReservations() {
  const q      = document.getElementById('filterRes').value.toLowerCase();
  const status = document.getElementById('filterStatus').value;
  document.querySelectorAll('#resTable tbody tr').forEach(row => {
    const roomMatch   = !q || row.dataset.room.includes(q);
    const statusMatch = !status || row.dataset.status === status;
    row.style.display = (roomMatch && statusMatch) ? '' : 'none';
  });
}
</script>
</body>
</html>