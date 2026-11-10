<?php
use App\Exceptions\MaterialNoTrobatException;
use App\Models\DVD;
use App\Models\Llibre;
use App\Models\Revista;

$id = (int) ($_GET['id'] ?? 0);
$m = $biblioteca->cercarPerId($id) ?? throw new MaterialNoTrobatException("No existeix cap material amb ID $id");
$titol = $m->getTitol();
require 'views/partials/header.php';
?>
<h1><?= e($m->getTitol()) ?></h1>
<ul class="list-group">
    <li class="list-item">Representació: <code><?= e((string) $m) ?></code></li>
    <li class="list-item">ID: <?= $m->getId() ?></li>
    <li class="list-item">Tipus: <?= e($m->getTipus()) ?></li>
    <li class="list-item">Autor: <?= e($m->getAutor()) ?></li>
    <li class="list-item">Any de publicació: <?= $m->getAnyPublicacio() ?></li>
    <?php
    // Dades específiques de cada subclasse
    $extra = match (true) {
        $m instanceof Llibre  => 'Pàgines: ' . $m->getNumeroPagines(),
        $m instanceof Revista => 'Número d\'edició: ' . $m->getNumeroEdicio(),
        $m instanceof DVD     => 'Durada: ' . $m->getDuracioFormatada(),
        default               => null,
    };
    if ($extra): ?><li class="list-item"><?= e($extra) ?></li><?php endif; ?>
    <li class="list-item">Estat: <?php require 'views/partials/estat.php'; ?></li>
    <li class="list-item">Multa per dia de retard: <?= number_format($m->calcularMulta(1), 2) ?> €</li>
</ul>
<p>
    <?php if ($m->isDisponible()): ?><a class="btn" href="index.php?page=prestec&material_id=<?= $m->getId() ?>">Prestar</a>
    <?php else: ?><a class="btn" href="index.php?page=retorn&material_id=<?= $m->getId() ?>">Retornar</a><?php endif; ?>
    <a class="btn" href="index.php?page=reserva&material_id=<?= $m->getId() ?>">Reservar</a>
</p>
<h2>Historial d'auditoria</h2>
<table class="table">
    <thead><tr><th>Data</th><th>Acció</th><th>Detalls</th></tr></thead>
    <tbody>
    <?php foreach ($m->obtenirHistorial() as $h): ?>
        <tr><td><?= e($h['data']) ?></td><td><?= e($h['accio']) ?></td><td><?= e($h['detalls']) ?></td></tr>
    <?php endforeach; ?>
    <?php if ($m->obtenirHistorial() === []): ?><tr><td colspan="3">Encara no hi ha cap acció registrada.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php require 'views/partials/footer.php'; ?>
