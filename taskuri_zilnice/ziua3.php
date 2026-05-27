<?php
// ziua3.php
// Task: verifică fiecare număr dintr-un array dacă este par sau impar
// Instrucțiuni folosite: if, for

$numere = [4, 7, 12, 3, 8, 15, 22, 9, 6, 11];

$pare   = 0;
$impare = 0;

?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Ziua 3 — Par sau Impar</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #0a0908; color: #e8e0d0; padding: 2rem; }
    h1   { color: #c9a84c; }
    table { border-collapse: collapse; margin: 1.5rem 0; }
    th, td { border: 1px solid #2a2520; padding: 0.5rem 1.5rem; text-align: center; }
    th { background: #1e1a14; color: #c9a84c; }
    .par    { color: #58d68d; font-weight: bold; }
    .impar  { color: #e07070; font-weight: bold; }
    .result { background: #161410; border: 1px solid #c9a84c44; padding: 1rem 1.5rem; border-radius: 6px; margin-top: 1rem; font-size: 1rem; }
  </style>
</head>
<body>

<h1>Ziua 3 — Verificare Par / Impar</h1>

<p>Array: [<?= implode(', ', $numere) ?>]</p>

<table>
  <thead>
    <tr><th>#</th><th>Număr</th><th>Tip</th></tr>
  </thead>
  <tbody>
    <?php
    for ($i = 0; $i < count($numere); $i++) {
        if ($numere[$i] % 2 == 0) {
            $tip = "<span class='par'>Par</span>";
            $pare++;
        } else {
            $tip = "<span class='impar'>Impar</span>";
            $impare++;
        }
        echo "<tr><td>" . ($i + 1) . "</td><td>" . $numere[$i] . "</td><td>$tip</td></tr>";
    }
    ?>
  </tbody>
</table>

<div class="result">
  ✅ Numere <span class="par">pare</span>: <strong><?= $pare ?></strong> &nbsp;|&nbsp;
  Numere <span class="impar">impare</span>: <strong><?= $impare ?></strong>
</div>

</body>
</html>