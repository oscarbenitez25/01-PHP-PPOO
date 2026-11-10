<?php $titol = 'Auditoria'; require 'views/partials/header.php'; ?>
<h1>🗂 Auditoria</h1>
<p>Historial d'accions de tots els materials.</p>
<table class="table">
    <thead><tr><th>Data</th><th>Material</th><th>Acció</th><th>Detalls</th></tr></thead>
    <tbody>
    <?php
    $files = [];
    foreach ($biblioteca->getMaterials() as $m) {
        foreach ($m->obtenirHistorial() as $h) {
            $files[] = $h + ['material' => $m];
        }
    }
    usort($files, fn($a, $b) => strcmp($b['data'], $a['data'])); // més recent primer
    foreach ($files as $f): ?>
        <tr>
            <td><?= e($f['data']) ?></td>
            <td><a href="index.php?page=material&id=<?= $f['material']->getId() ?>"><?= e($f['material']->getTitol()) ?></a></td>
            <td><?= e($f['accio']) ?></td><td><?= e($f['detalls']) ?></td>
        </tr>
    <?php endforeach; ?>
    <?php if ($files === []): ?><tr><td colspan="4">Encara no hi ha cap acció registrada.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php require 'views/partials/footer.php'; ?>
