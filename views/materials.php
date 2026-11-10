<?php
$titol = 'Materials';
// Filtre per tipus amb match
$tipus = match (strtolower($_GET['type'] ?? '')) {
    'llibre'  => 'Llibre',
    'revista' => 'Revista',
    'dvd'     => 'DVD',
    default   => null,
};
$materials = $tipus !== null ? $biblioteca->llistarPerTipus($tipus) : array_values($biblioteca->getMaterials());
require 'views/partials/header.php';
?>
<h1>📚 Materials<?= $tipus ? ' · ' . e($tipus) : '' ?></h1>
<p class="filters">
    <a class="btn" href="index.php?page=materials">Tots</a>
    <a class="btn" href="index.php?page=materials&type=llibre">Llibres</a>
    <a class="btn" href="index.php?page=materials&type=revista">Revistes</a>
    <a class="btn" href="index.php?page=materials&type=dvd">DVDs</a>
</p>
<table class="table">
    <thead><tr><th>ID</th><th>Tipus</th><th>Títol</th><th>Autor</th><th>Any</th><th>Estat</th><th>Accions</th></tr></thead>
    <tbody>
    <?php foreach ($materials as $m): ?>
        <tr>
            <td><?= $m->getId() ?></td>
            <td><?= e($m->getTipus()) ?></td>
            <td><?= e($m->getTitol()) ?></td>
            <td><?= e($m->getAutor()) ?></td>
            <td><?= $m->getAnyPublicacio() ?></td>
            <td><?php require 'views/partials/estat.php'; ?></td>
            <td>
                <a href="index.php?page=material&id=<?= $m->getId() ?>">Detall</a>
                <?php if ($m->isDisponible()): ?> · <a href="index.php?page=prestec&material_id=<?= $m->getId() ?>">Prestar</a>
                <?php else: ?> · <a href="index.php?page=retorn&material_id=<?= $m->getId() ?>">Retornar</a><?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if ($materials === []): ?><tr><td colspan="7">No hi ha materials.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php require 'views/partials/footer.php'; ?>
