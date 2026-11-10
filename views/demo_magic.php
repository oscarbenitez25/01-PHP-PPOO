<?php
use App\Exceptions\EmailInvalidException;
use App\Models\Usuari;

$titol = 'Demo mètodes màgics';
$material = $biblioteca->cercarPerId(1);
$u = new Usuari(99, 'Usuari Demo', 'demo@mail.com'); // objecte temporal: no toca les dades reals
$resultats = [];

// __toString
$resultats['__toString (Material)'] = (string) $material;
$resultats['__toString (Usuari)'] = (string) $u;

// __call
$resultats['$biblioteca->getLlibres()'] = count($biblioteca->getLlibres()) . ' llibres';
$resultats['$biblioteca->getRevistes()'] = count($biblioteca->getRevistes()) . ' revistes';
$resultats['$biblioteca->getDVDs()'] = count($biblioteca->getDVDs()) . ' DVDs';
$resultats['$biblioteca->getNom()'] = $biblioteca->getNom();
try {
    $biblioteca->metodeInexistent();
} catch (BadMethodCallException $e) {
    $resultats['__call (mètode inexistent)'] = 'BadMethodCallException: ' . $e->getMessage();
}

// __get
$resultats['$u->nom (__get)'] = $u->nom;
$resultats['$u->email (__get)'] = $u->email;
$resultats['$u->dataRegistre (__get)'] = $u->dataRegistre->format('d/m/Y H:i');
$resultats['$u->inexistent (__get)'] = var_export($u->inexistent, true);

// __set
$u->email = 'nou@mail.com';
$resultats['$u->email = "nou@mail.com" (__set)'] = 'OK → ' . $u->email;
try {
    $u->email = 'no-es-un-email';
} catch (EmailInvalidException $e) {
    $resultats['__set email invàlid'] = 'EmailInvalidException: ' . $e->getMessage();
}
try {
    $u->nom = 'Un altre nom'; // __set no permès
} catch (LogicException $e) {
    $resultats['__set no permès ($u->nom)'] = 'LogicException: ' . $e->getMessage();
}

require 'views/partials/header.php';
?>
<h1>🔧 Demo · Mètodes màgics</h1>
<table class="table">
    <thead><tr><th>Prova</th><th>Resultat</th></tr></thead>
    <tbody>
    <?php foreach ($resultats as $prova => $r): ?>
        <tr><td><code><?= e($prova) ?></code></td><td><?= e($r) ?></td></tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require 'views/partials/footer.php'; ?>
