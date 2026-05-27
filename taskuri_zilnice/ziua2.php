<?php

error_log("=== Ziua 2: Script PHP pornit la " . date('Y-m-d H:i:s') . " ===");


$numeHotel   = "Grand Hotel";          
$nrCamere    = 4;                       
$pretMediu   = 325.75;                 
$esteActiv   = true;                   
$facilitati  = ["Wi-Fi", "Parcare", "Restaurant", "Spa"]; 

error_log("Hotel: $numeHotel | Camere: $nrCamere | Preț mediu: $pretMediu EUR");


function calculeazaTotal(int $nopti, float $pret): float {
    return $nopti * $pret;
}

function salutOaspete(string $nume): string {
    return "Bun venit la Grand Hotel, " . htmlspecialchars($nume, ENT_QUOTES) . "!";
}

$total = calculeazaTotal(3, $pretMediu);
error_log("Total calculat pentru 3 nopți: " . $total . " EUR");

?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ziua 2 — Exercițiu PHP</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #0a0908; color: #e8e0d0; padding: 2rem; }
    h1   { color: #c9a84c; font-size: 1.6rem; border-bottom: 1px solid #c9a84c33; padding-bottom: 0.5rem; }
    h2   { color: #c9a84c; font-size: 1.1rem; margin-top: 2rem; }
    .box { background: #161410; border: 1px solid #2a2520; border-radius: 6px; padding: 1.25rem; margin: 1rem 0; }
    .label { font-size: 0.7rem; letter-spacing: 2px; text-transform: uppercase; color: #9a8e7a; margin-bottom: 0.4rem; }
    .val  { color: #e0c070; font-size: 1rem; }
    .tag  { display:inline-block; background:#c9a84c22; color:#c9a84c; font-size:0.72rem; padding:0.2rem 0.6rem; border-radius:3px; margin:0.2rem; }
    .msg-success { background:#1a3d2b; color:#58d68d; border-left:3px solid #58d68d; padding:0.75rem 1rem; border-radius:4px; }
    table { border-collapse: collapse; width: 100%; margin-top: 0.5rem; }
    th, td { border: 1px solid #2a2520; padding: 0.5rem 0.75rem; font-size: 0.85rem; text-align: left; }
    th { background: #1e1a14; color: #c9a84c; font-size: 0.7rem; letter-spacing: 1px; text-transform: uppercase; }
    code { background: #1e1a14; color: #e0c070; padding: 0.15rem 0.4rem; border-radius: 3px; font-size: 0.85rem; }
  </style>
</head>
<body>

<h1>🏨 Ziua 2 — Exercițiu PHP: Variabile și Funcții</h1>

<div class="msg-success">
  ✅ Scriptul PHP rulează corect! Mesajele sunt trimise și în consolă (error_log → Apache log).
</div>

<h2>1. Variabile și tipuri de date</h2>
<div class="box">
  <div class="label">String</div>
  <div class="val">$numeHotel = "<?= $numeHotel ?>"</div>
</div>
<div class="box">
  <div class="label">Integer</div>
  <div class="val">$nrCamere = <?= $nrCamere ?></div>
</div>
<div class="box">
  <div class="label">Float</div>
  <div class="val">$pretMediu = <?= $pretMediu ?> EUR</div>
</div>
<div class="box">
  <div class="label">Boolean</div>
  <div class="val">$esteActiv = <?= $esteActiv ? "true" : "false" ?></div>
</div>
<div class="box">
  <div class="label">Array</div>
  <div class="val">
    <?php foreach ($facilitati as $f): ?>
      <span class="tag"><?= htmlspecialchars($f) ?></span>
    <?php endforeach; ?>
  </div>
</div>

<h2>2. Funcții PHP</h2>
<div class="box">
  <div class="label">calculeazaTotal(3 nopți, <?= $pretMediu ?> EUR/noapte)</div>
  <div class="val">Rezultat: <strong><?= $total ?> EUR</strong></div>
</div>
<div class="box">
  <div class="label">salutOaspete("Ion Popescu")</div>
  <div class="val"><?= salutOaspete("Ion Popescu") ?></div>
</div>

<h2>3. Structuri de control</h2>
<div class="box">
  <div class="label">if / else</div>
  <div class="val">
    <?php if ($nrCamere > 0): ?>
      Hotelul are <strong><?= $nrCamere ?></strong> camere disponibile.
    <?php else: ?>
      Nu există camere disponibile.
    <?php endif; ?>
  </div>
</div>

<div class="box">
  <div class="label">foreach — lista facilităților</div>
  <table>
    <thead><tr><th>#</th><th>Facilitate</th><th>Disponibil</th></tr></thead>
    <tbody>
      <?php foreach ($facilitati as $index => $f): ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <td><?= htmlspecialchars($f) ?></td>
          <td style="color:#58d68d;">✔ Da</td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<h2>4. Funcții PHP built-in utile</h2>
<div class="box">
  <div class="label">date() — data și ora curentă</div>
  <div class="val"><code><?= date('d.m.Y H:i:s') ?></code></div>
</div>
<div class="box">
  <div class="label">strtoupper() / strtolower()</div>
  <div class="val">
    <code>strtoupper("grand hotel")</code> → <?= strtoupper("grand hotel") ?><br>
    <code>strtolower("GRAND HOTEL")</code> → <?= strtolower("GRAND HOTEL") ?>
  </div>
</div>
<div class="box">
  <div class="label">strlen() / str_word_count()</div>
  <div class="val">
    Lungimea "<?= $numeHotel ?>": <code><?= strlen($numeHotel) ?></code> caractere<br>
    Nr. cuvinte: <code><?= str_word_count($numeHotel) ?></code>
  </div>
</div>
<div class="box">
  <div class="label">count() / array_sum() pe array-uri numerice</div>
  <?php $preturi = [180, 320, 650, 150]; ?>
  <div class="val">
    Prețuri camere: <code>[<?= implode(', ', $preturi) ?>]</code><br>
    count() = <code><?= count($preturi) ?></code> | 
    array_sum() = <code><?= array_sum($preturi) ?></code> EUR |
    max() = <code><?= max($preturi) ?></code> EUR
  </div>
</div>

<h2>5. Mesaje în consolă (error_log)</h2>
<div class="box">
  <div class="label">Cum funcționează error_log în PHP</div>
  <div class="val">
    <code>error_log("Mesaj de test");</code><br><br>
    Mesajele trimise de acest script:<br>
    <span class="tag">Ziua 2: Script PHP pornit la <?= date('Y-m-d H:i:s') ?></span><br>
    <span class="tag">Hotel: <?= $numeHotel ?> | Camere: <?= $nrCamere ?></span><br>
    <span class="tag">Total calculat: <?= $total ?> EUR</span><br><br>
    Le poți vedea în XAMPP → <strong>Apache logs</strong> → <code>C:\xampp\apache\logs\error.log</code>
  </div>
</div>

<?php

error_log("=== Script finalizat. Total afișat: $total EUR ===");
?>

</body>
</html>
