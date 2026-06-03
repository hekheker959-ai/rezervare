<?php


$rezultat = [];
$erori    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actiune = $_POST['actiune'] ?? '';

    if ($actiune === 'calcul_rezervare') {
        $camera  = trim($_POST['camera'] ?? '');
        $checkin = trim($_POST['checkin'] ?? '');
        $checkout = trim($_POST['checkout'] ?? '');
        $oaspeti = intval($_POST['oaspeti'] ?? 0);

        $preturi = [
            'standard'      => 120,
            'deluxe'        => 180,
            'executive'     => 280,
            'prezidentiala' => 450,
        ];

        if (!$camera || !$checkin || !$checkout) {
            $erori[] = 'Completeaza toate campurile obligatorii.';
        } elseif (!isset($preturi[$camera])) {
            $erori[] = 'Camera selectata nu exista.';
        } elseif (strtotime($checkout) <= strtotime($checkin)) {
            $erori[] = 'Data de checkout trebuie sa fie dupa checkin.';
        } elseif ($oaspeti < 1 || $oaspeti > 4) {
            $erori[] = 'Numarul de oaspeti trebuie sa fie intre 1 si 4.';
        } else {
            $nopti = (int)((strtotime($checkout) - strtotime($checkin)) / 86400);
            $pret  = $preturi[$camera];
            $total = $nopti * $pret;

            $rezultat = [
                'camera'  => ucfirst($camera),
                'checkin' => $checkin,
                'checkout'=> $checkout,
                'nopti'   => $nopti,
                'oaspeti' => $oaspeti,
                'pret_noapte' => $pret,
                'total'   => $total,
            ];

            error_log("Rezervare calculata: Camera=$camera, Nopti=$nopti, Total=$total EUR");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ziua 6 — Formulare PHP</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #0a0908; color: #e8e0d0; padding: 2rem; max-width: 700px; margin: 0 auto; }
    h1   { color: #c9a84c; font-size: 1.5rem; border-bottom: 1px solid #c9a84c44; padding-bottom: 0.5rem; margin-bottom: 1.5rem; }
    h2   { color: #c9a84c; font-size: 1.1rem; margin: 1.75rem 0 1rem; }
    label { display: block; font-size: 0.7rem; letter-spacing: 2px; text-transform: uppercase; color: #9a8e7a; margin-bottom: 0.4rem; }
    input, select { width: 100%; padding: 0.7rem 1rem; background: #1e1a14; border: 1px solid #2a2520; color: #e8e0d0; font-family: inherit; font-size: 0.9rem; margin-bottom: 1rem; box-sizing: border-box; }
    input:focus, select:focus { outline: none; border-color: #c9a84c; }
    button { background: #c9a84c; color: #0a0908; border: none; padding: 0.75rem 2rem; font-size: 0.8rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; cursor: pointer; width: 100%; }
    button:hover { background: #e0c070; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .eroare { background: rgba(139,32,32,0.15); border-left: 3px solid #e07070; color: #e07070; padding: 0.75rem 1rem; margin-bottom: 1rem; font-size: 0.85rem; }
    .success { background: rgba(29,158,117,0.1); border-left: 3px solid #1D9E75; color: #58d68d; padding: 1.25rem; margin-top: 1.5rem; }
    .success table { width: 100%; border-collapse: collapse; margin-top: 0.75rem; }
    .success th, .success td { padding: 0.5rem 0.75rem; font-size: 0.85rem; text-align: left; border-bottom: 1px solid rgba(29,158,117,0.2); }
    .success th { font-size: 0.65rem; letter-spacing: 2px; text-transform: uppercase; color: #1D9E75; }
    .total-row td { color: #c9a84c; font-size: 1.1rem; font-weight: 600; border-top: 1px solid #c9a84c44; }
    .info { background: #161410; border: 1px solid #2a2520; padding: 1rem; margin-top: 2rem; font-size: 0.8rem; color: #9a8e7a; line-height: 1.7; }
    code { background: #1e1a14; color: #e0c070; padding: 0.1rem 0.4rem; font-size: 0.82rem; }
  </style>
</head>
<body>

<h1>Ziua 6 — Formulare si transmitere date prin POST</h1>

<?php foreach ($erori as $e): ?>
  <div class="eroare">⚠ <?= htmlspecialchars($e) ?></div>
<?php endforeach; ?>

<form method="POST" action="ziua6.php">
  <input type="hidden" name="actiune" value="calcul_rezervare">

  <h2>Calculator rezervare camera</h2>

  <label>Camera</label>
  <select name="camera">
    <option value="">-- Selecteaza camera --</option>
    <option value="standard"      <?= ($_POST['camera']??'')==='standard'?'selected':'' ?>>Camera Standard — €120/noapte</option>
    <option value="deluxe"        <?= ($_POST['camera']??'')==='deluxe'?'selected':'' ?>>Camera Deluxe — €180/noapte</option>
    <option value="executive"     <?= ($_POST['camera']??'')==='executive'?'selected':'' ?>>Suite Executive — €280/noapte</option>
    <option value="prezidentiala" <?= ($_POST['camera']??'')==='prezidentiala'?'selected':'' ?>>Suite Prezidentiala — €450/noapte</option>
  </select>

  <div class="form-row">
    <div>
      <label>Check-in</label>
      <input type="date" name="checkin" value="<?= htmlspecialchars($_POST['checkin'] ?? '') ?>">
    </div>
    <div>
      <label>Check-out</label>
      <input type="date" name="checkout" value="<?= htmlspecialchars($_POST['checkout'] ?? '') ?>">
    </div>
  </div>

  <label>Numar oaspeti</label>
  <select name="oaspeti">
    <?php for ($i = 1; $i <= 4; $i++): ?>
      <option value="<?= $i ?>" <?= (($_POST['oaspeti']??1)==$i)?'selected':'' ?>>
        <?= $i ?> oaspete<?= $i > 1 ? 'i' : '' ?>
      </option>
    <?php endfor; ?>
  </select>

  <button type="submit">Calculeaza costul</button>
</form>


<?php if (!empty($rezultat)): ?>
<div class="success">
  ✅ Rezervare calculata cu succes!
  <table>
    <thead><tr><th>Camp</th><th>Valoare</th></tr></thead>
    <tbody>
      <tr><td>Camera</td><td><?= htmlspecialchars($rezultat['camera']) ?></td></tr>
      <tr><td>Check-in</td><td><?= htmlspecialchars($rezultat['checkin']) ?></td></tr>
      <tr><td>Check-out</td><td><?= htmlspecialchars($rezultat['checkout']) ?></td></tr>
      <tr><td>Numar nopti</td><td><?= $rezultat['nopti'] ?></td></tr>
      <tr><td>Numar oaspeti</td><td><?= $rezultat['oaspeti'] ?></td></tr>
      <tr><td>Pret/noapte</td><td>€<?= $rezultat['pret_noapte'] ?></td></tr>
      <tr class="total-row"><td>TOTAL</td><td>€<?= number_format($rezultat['total'], 0) ?></td></tr>
    </tbody>
  </table>
</div>
<?php endif; ?>

<div class="info">
  <strong style="color:#c9a84c;">Concepte PHP demonstrate in acest script:</strong><br><br>
  <code>$_SERVER['REQUEST_METHOD']</code> — verifica daca formularul a fost trimis prin POST<br>
  <code>$_POST['camp']</code> — citeste datele trimise prin formularul HTML<br>
  <code>trim()</code> — elimina spatiile de la inceputul si sfarsitul unui string<br>
  <code>intval()</code> — converteste o valoare la integer<br>
  <code>strtotime()</code> — converteste o data in timestamp Unix pentru calcule<br>
  <code>filter_var($email, FILTER_VALIDATE_EMAIL)</code> — valideaza adresa de email<br>
  <code>htmlspecialchars()</code> — protejeaza impotriva atacurilor XSS<br>
  <code>number_format()</code> — formateaza un numar pentru afisare<br>
</div>

</body>
</html>
