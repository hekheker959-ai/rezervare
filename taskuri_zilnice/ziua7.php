<?php


$mesaj = '';
$tipMesaj = '';
$dataFile = __DIR__ . '/ziua7_date.json';

function citesteDate(string $file): array {
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true) ?? [];
}

function salveazaDate(string $file, array $date): void {
    file_put_contents($file,
        json_encode($date, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actiune = $_POST['actiune'] ?? '';

    if ($actiune === 'adauga') {
        $nume  = trim($_POST['nume'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $oras  = trim($_POST['oras'] ?? '');

        if (!$nume || !$email) {
            $mesaj = 'Numele si email-ul sunt obligatorii!';
            $tipMesaj = 'eroare';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mesaj = 'Adresa de email nu este valida!';
            $tipMesaj = 'eroare';
        } else {
            $date = citesteDate($dataFile);

            foreach ($date as $d) {
                if (strtolower($d['email']) === strtolower($email)) {
                    $mesaj = 'Acest email este deja inregistrat!';
                    $tipMesaj = 'eroare';
                    break;
                }
            }

            if ($tipMesaj !== 'eroare') {
                $date[] = [
                    'id'         => uniqid('id_', true),
                    'nume'       => htmlspecialchars($nume, ENT_QUOTES),
                    'email'      => strtolower($email),
                    'oras'       => htmlspecialchars($oras, ENT_QUOTES),
                    'creat_la'   => date('d.m.Y H:i:s'),
                ];
                salveazaDate($dataFile, $date);
                $mesaj = "Utilizatorul \"$nume\" a fost adaugat cu succes!";
                $tipMesaj = 'succes';
                error_log("Ziua7: Utilizator nou adaugat — $nume ($email)");
            }
        }
    }

    if ($actiune === 'sterge') {
        $id = $_POST['id'] ?? '';
        $date = citesteDate($dataFile);
        $date = array_values(array_filter($date, fn($d) => $d['id'] !== $id));
        salveazaDate($dataFile, $date);
        $mesaj = 'Utilizatorul a fost sters!';
        $tipMesaj = 'succes';
    }
}

$listaDate = citesteDate($dataFile);
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Ziua 7 — Salvare JSON</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #0a0908; color: #e8e0d0; padding: 2rem; max-width: 800px; margin: 0 auto; }
    h1 { color: #c9a84c; font-size: 1.5rem; border-bottom: 1px solid #c9a84c44; padding-bottom: 0.5rem; }
    h2 { color: #c9a84c; font-size: 1.05rem; margin: 1.75rem 0 1rem; }
    label { display: block; font-size: 0.7rem; letter-spacing: 2px; text-transform: uppercase; color: #9a8e7a; margin-bottom: 0.4rem; }
    input { width: 100%; padding: 0.7rem 1rem; background: #1e1a14; border: 1px solid #2a2520; color: #e8e0d0; font-family: inherit; font-size: 0.9rem; margin-bottom: 1rem; box-sizing: border-box; }
    input:focus { outline: none; border-color: #c9a84c; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    button[type="submit"] { background: #c9a84c; color: #0a0908; border: none; padding: 0.75rem 2rem; font-size: 0.8rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; cursor: pointer; }
    button[type="submit"]:hover { background: #e0c070; }
    .eroare { background: rgba(139,32,32,0.15); border-left: 3px solid #e07070; color: #e07070; padding: 0.75rem 1rem; margin-bottom: 1rem; font-size: 0.85rem; }
    .succes { background: rgba(29,158,117,0.1); border-left: 3px solid #1D9E75; color: #58d68d; padding: 0.75rem 1rem; margin-bottom: 1rem; font-size: 0.85rem; }
    table { width: 100%; border-collapse: collapse; margin-top: 0.5rem; font-size: 0.85rem; }
    th { text-align: left; padding: 0.6rem 0.75rem; font-size: 0.65rem; letter-spacing: 2px; text-transform: uppercase; color: #c9a84c; border-bottom: 1px solid #c9a84c44; }
    td { padding: 0.75rem; border-bottom: 1px solid #2a2520; color: #e8e0d0; vertical-align: middle; }
    tr:hover td { background: rgba(200,168,76,0.04); }
    .btn-del { background: rgba(139,32,32,0.3); color: #e07070; border: 1px solid #e07070; padding: 0.3rem 0.8rem; font-size: 0.72rem; cursor: pointer; letter-spacing: 1px; }
    .btn-del:hover { background: rgba(139,32,32,0.5); }
    .gol { text-align: center; padding: 2rem; color: #5a5040; font-size: 0.85rem; }
    .json-box { background: #0f0e0c; border: 1px solid #2a2520; padding: 1rem; font-family: 'Courier New', monospace; font-size: 0.78rem; color: #e0c070; overflow-x: auto; margin-top: 0.5rem; max-height: 200px; overflow-y: auto; }
    .badge { background: #c9a84c22; color: #c9a84c; font-size: 0.65rem; padding: 0.15rem 0.5rem; border-radius: 3px; font-weight: 600; }
  </style>
</head>
<body>

<h1>Ziua 7 — Salvarea si citirea datelor din JSON</h1>

<?php if ($mesaj): ?>
  <div class="<?= $tipMesaj ?>"><?= htmlspecialchars($mesaj) ?></div>
<?php endif; ?>

<h2>Adauga un utilizator nou</h2>
<form method="POST">
  <input type="hidden" name="actiune" value="adauga">
  <div class="form-row">
    <div>
      <label>Nume *</label>
      <input type="text" name="nume" placeholder="Ex: Ion Popescu"
             value="<?= htmlspecialchars($_POST['nume'] ?? '') ?>">
    </div>
    <div>
      <label>Email *</label>
      <input type="email" name="email" placeholder="Ex: ion@email.com"
             value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </div>
  </div>
  <label>Oras (optional)</label>
  <input type="text" name="oras" placeholder="Ex: Chisinau"
         value="<?= htmlspecialchars($_POST['oras'] ?? '') ?>">
  <button type="submit">Salveaza in JSON</button>
</form>

<h2>
  Utilizatori salvati
  <span class="badge"><?= count($listaDate) ?> total</span>
</h2>

<?php if (empty($listaDate)): ?>
  <div class="gol">Nu exista niciun utilizator salvat inca.</div>
<?php else: ?>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Nume</th>
        <th>Email</th>
        <th>Oras</th>
        <th>Data</th>
        <th>Actiuni</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($listaDate as $i => $d): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><strong><?= htmlspecialchars($d['nume']) ?></strong></td>
          <td><?= htmlspecialchars($d['email']) ?></td>
          <td><?= htmlspecialchars($d['oras'] ?? '—') ?></td>
          <td style="font-size:0.75rem;color:#7a7060;"><?= htmlspecialchars($d['creat_la']) ?></td>
          <td>
            <form method="POST" onsubmit="return confirm('Stergi utilizatorul?')">
              <input type="hidden" name="actiune" value="sterge">
              <input type="hidden" name="id" value="<?= htmlspecialchars($d['id']) ?>">
              <button type="submit" class="btn-del">✕ Sterge</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<h2>Continutul fisierului ziua7_date.json</h2>
<div class="json-box"><?= htmlspecialchars(
  file_exists($dataFile)
    ? file_get_contents($dataFile)
    : '[]'
) ?></div>

</body>
</html>
