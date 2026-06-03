<?php
// ziua8.php
// Task: Sesiuni PHP, pagini protejate, afisare date din JSON
// Concepte: session_start, $_SESSION, isset, header, json_decode

session_start();

$actiune  = $_POST['actiune'] ?? $_GET['actiune'] ?? '';
$mesaj    = '';
$tipMesaj = '';

// Simulare login simplu (fara baza de date, doar pentru exercitiu)
$utilizatoriDemo = [
    ['username' => 'admin',    'password' => '1234', 'rol' => 'Administrator'],
    ['username' => 'ion',      'password' => 'abcd', 'rol' => 'Oaspete'],
    ['username' => 'maria',    'password' => 'pass1', 'rol' => 'Oaspete'],
];

if ($actiune === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $gasit = false;
    foreach ($utilizatoriDemo as $u) {
        if ($u['username'] === $username && $u['password'] === $password) {
            $_SESSION['user']     = $username;
            $_SESSION['rol']      = $u['rol'];
            $_SESSION['login_la'] = date('H:i:s');
            $gasit = true;
            $mesaj = "Autentificat ca: $username";
            $tipMesaj = 'succes';
            break;
        }
    }
    if (!$gasit) {
        $mesaj = 'Utilizator sau parola gresita!';
        $tipMesaj = 'eroare';
    }
}

if ($actiune === 'logout') {
    session_destroy();
    header('Location: ziua8.php?msg=deconectat');
    exit;
}

$msgGet = $_GET['msg'] ?? '';
if ($msgGet === 'deconectat') {
    $mesaj = 'Ai fost deconectat cu succes.';
    $tipMesaj = 'succes';
}

$esteLogat = isset($_SESSION['user']);

// Date fictive JSON pentru afisare
$rezervariJson = '[
    {"id":"r_001","camera":"Camera Deluxe","checkin":"2025-07-10","checkout":"2025-07-13","nopti":3,"total":540,"status":"confirmed"},
    {"id":"r_002","camera":"Suite Executive","checkin":"2025-08-01","checkout":"2025-08-05","nopti":4,"total":1120,"status":"confirmed"},
    {"id":"r_003","camera":"Camera Standard","checkin":"2025-04-20","checkout":"2025-04-22","nopti":2,"total":240,"status":"cancelled"}
]';
$rezervari = json_decode($rezervariJson, true);
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Ziua 8 — Sesiuni si pagini protejate</title>
  <style>
    body { font-family:'Segoe UI',sans-serif; background:#0a0908; color:#e8e0d0; padding:2rem; max-width:800px; margin:0 auto; }
    h1 { color:#c9a84c; font-size:1.5rem; border-bottom:1px solid #c9a84c44; padding-bottom:0.5rem; }
    h2 { color:#c9a84c; font-size:1.05rem; margin:1.75rem 0 0.75rem; }
    label { display:block; font-size:0.7rem; letter-spacing:2px; text-transform:uppercase; color:#9a8e7a; margin-bottom:0.4rem; }
    input { width:100%; padding:0.7rem 1rem; background:#1e1a14; border:1px solid #2a2520; color:#e8e0d0; font-family:inherit; font-size:0.9rem; margin-bottom:1rem; box-sizing:border-box; }
    input:focus { outline:none; border-color:#c9a84c; }
    .btn-gold { background:#c9a84c; color:#0a0908; border:none; padding:0.7rem 2rem; font-size:0.8rem; font-weight:600; letter-spacing:2px; text-transform:uppercase; cursor:pointer; width:100%; }
    .btn-gold:hover { background:#e0c070; }
    .btn-red { background:rgba(139,32,32,0.3); color:#e07070; border:1px solid #e07070; padding:0.5rem 1.5rem; font-size:0.8rem; cursor:pointer; letter-spacing:1px; }
    .eroare { background:rgba(139,32,32,0.15); border-left:3px solid #e07070; color:#e07070; padding:0.75rem 1rem; margin-bottom:1rem; font-size:0.85rem; }
    .succes { background:rgba(29,158,117,0.1); border-left:3px solid #1D9E75; color:#58d68d; padding:0.75rem 1rem; margin-bottom:1rem; font-size:0.85rem; }
    .info-box { background:#161410; border:1px solid #2a2520; padding:1.25rem; border-radius:6px; margin-bottom:1.5rem; }
    .info-box .row { display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:0.4rem; }
    .info-box .row span:first-child { color:#9a8e7a; font-size:0.7rem; letter-spacing:1px; text-transform:uppercase; }
    .info-box .row span:last-child { color:#e8e0d0; }
    .protejat { background:rgba(139,32,32,0.1); border:1px solid #e07070; padding:2rem; text-align:center; border-radius:6px; }
    .protejat p { color:#e07070; font-size:0.9rem; margin-bottom:1rem; }
    table { width:100%; border-collapse:collapse; font-size:0.82rem; }
    th { text-align:left; padding:0.6rem 0.75rem; font-size:0.65rem; letter-spacing:2px; text-transform:uppercase; color:#c9a84c; border-bottom:1px solid #c9a84c44; }
    td { padding:0.75rem; border-bottom:1px solid #2a2520; }
    .confirmed { background:rgba(29,158,117,0.12); color:#58d68d; font-size:0.65rem; padding:0.2rem 0.6rem; letter-spacing:1px; text-transform:uppercase; }
    .cancelled { background:rgba(139,32,32,0.12); color:#e07070; font-size:0.65rem; padding:0.2rem 0.6rem; letter-spacing:1px; text-transform:uppercase; }
    .stat-row { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin:1rem 0; }
    .stat { background:#161410; border:1px solid #2a2520; padding:1rem; text-align:center; }
    .stat .num { font-size:1.8rem; color:#c9a84c; }
    .stat .lbl { font-size:0.65rem; color:#7a7060; letter-spacing:1.5px; text-transform:uppercase; margin-top:0.25rem; }
    code { background:#1e1a14; color:#e0c070; padding:0.1rem 0.4rem; font-size:0.82rem; }
  </style>
</head>
<body>

<h1>Ziua 8 — Sesiuni PHP si pagini protejate</h1>

<?php if ($mesaj): ?>
  <div class="<?= $tipMesaj ?>"><?= htmlspecialchars($mesaj) ?></div>
<?php endif; ?>

<!-- SECTIUNEA 1: LOGIN / STARE SESIUNE -->
<h2>1. Autentificare si sesiune PHP</h2>

<?php if (!$esteLogat): ?>
  <form method="POST">
    <input type="hidden" name="actiune" value="login">
    <label>Utilizator</label>
    <input type="text" name="username" placeholder="admin / ion / maria">
    <label>Parola</label>
    <input type="password" name="password" placeholder="1234 / abcd / pass1">
    <button type="submit" class="btn-gold">Autentificare</button>
  </form>
  <p style="font-size:0.78rem;color:#7a7060;margin-top:0.75rem;">
    Conturi demo: <code>admin/1234</code> &nbsp;|&nbsp; <code>ion/abcd</code> &nbsp;|&nbsp; <code>maria/pass1</code>
  </p>

<?php else: ?>
  <div class="info-box">
    <div class="row"><span>Utilizator</span><span><?= htmlspecialchars($_SESSION['user']) ?></span></div>
    <div class="row"><span>Rol</span><span><?= htmlspecialchars($_SESSION['rol']) ?></span></div>
    <div class="row"><span>Autentificat la</span><span><?= htmlspecialchars($_SESSION['login_la']) ?></span></div>
    <div class="row"><span>Session ID</span><span style="font-size:0.72rem;color:#5a5040;"><?= session_id() ?></span></div>
  </div>
  <form method="POST">
    <input type="hidden" name="actiune" value="logout">
    <button type="submit" class="btn-red">Deconectare</button>
  </form>
<?php endif; ?>

<!-- SECTIUNEA 2: PAGINA PROTEJATA -->
<h2>2. Continut protejat (accesibil doar dupa login)</h2>

<?php if (!$esteLogat): ?>
  <div class="protejat">
    <p>🔒 Aceasta sectiune este accesibila doar utilizatorilor autentificati.</p>
    <p style="font-size:0.78rem;color:#5a5040;">In aplicatia reala: <code>requireLogin()</code> redirectioneaza automat catre login.php</p>
  </div>

<?php else: ?>
  <!-- SECTIUNEA 3: AFISARE DATE JSON -->
  <h2>3. Rezervarile mele — date citite din JSON</h2>

  <?php
    $confirmate = count(array_filter($rezervari, fn($r) => $r['status'] === 'confirmed'));
    $totalNopti = array_sum(array_column($rezervari, 'nopti'));
    $totalEur   = array_sum(array_column(
      array_filter($rezervari, fn($r) => $r['status'] === 'confirmed'),
      'total'
    ));
  ?>

  <div class="stat-row">
    <div class="stat"><div class="num"><?= count($rezervari) ?></div><div class="lbl">Total rezervari</div></div>
    <div class="stat"><div class="num"><?= $totalNopti ?></div><div class="lbl">Nopti totale</div></div>
    <div class="stat"><div class="num">€<?= number_format($totalEur) ?></div><div class="lbl">Total cheltuit</div></div>
  </div>

  <table>
    <thead>
      <tr><th>Camera</th><th>Check-in</th><th>Check-out</th><th>Nopti</th><th>Total</th><th>Status</th></tr>
    </thead>
    <tbody>
      <?php foreach ($rezervari as $r): ?>
        <tr>
          <td><strong><?= htmlspecialchars($r['camera']) ?></strong></td>
          <td><?= htmlspecialchars($r['checkin']) ?></td>
          <td><?= htmlspecialchars($r['checkout']) ?></td>
          <td><?= intval($r['nopti']) ?></td>
          <td style="color:#c9a84c;font-weight:600;">€<?= number_format($r['total']) ?></td>
          <td><span class="<?= $r['status'] ?>"><?= $r['status'] === 'confirmed' ? 'Confirmata' : 'Anulata' ?></span></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h2 style="margin-top:1.5rem;">4. Functii PHP folosite azi</h2>
  <div class="info-box" style="font-size:0.8rem;line-height:1.9;">
    <code>session_start()</code> — porneste sau continua o sesiune PHP<br>
    <code>$_SESSION['cheie'] = valoare</code> — salveaza date in sesiune<br>
    <code>isset($_SESSION['cheie'])</code> — verifica daca exista o variabila de sesiune<br>
    <code>session_destroy()</code> — sterge sesiunea la logout<br>
    <code>json_decode($json, true)</code> — transforma JSON in array PHP<br>
    <code>array_filter()</code> — filtreaza elementele unui array dupa o conditie<br>
    <code>array_sum(array_column())</code> — calculeaza suma unei coloane din array<br>
    <code>header('Location: ...')</code> — redirectioneaza catre alta pagina<br>
  </div>
<?php endif; ?>

</body>
</html>
