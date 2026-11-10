<?php
/** Retorna un material (mostrant la multa) o llista els préstecs actius per triar. */
$titol = 'Retorn';
$materialId = isset($_GET['material_id']) ? (int) $_GET['material_id'] : null;

if ($materialId !== null) {
    $material = $biblioteca->cercarPerId($materialId);
    $multa = $biblioteca->retornarMaterial($materialId); // pot llançar excepcions
    require 'views/partials/header.php';
    ?>
    <h1>↩️ Retorn</h1>
    <div class="alert ok">Material retornat: <strong><?= e($material->getTitol()) ?></strong></div>
    <?php if ($multa > 0): ?>
        <div class="alert err">Multa per retard: <strong><?= number_format($multa, 2) ?> €</strong></div>
    <?php else: ?>
        <div class="alert ok">Sense multa. 👍</div>
    <?php endif; ?>
    <p><a class="btn" href="index.php?page=prestecs">Veure préstecs actius</a></p>
    <?php
} else {
    require 'views/partials/header.php';
    ?>
    <h1>↩️ Retornar un material</h1>
    <ul class="list-group">
    <?php foreach ($biblioteca->llistarPrestecsActius() as $p): ?>
        <li class="list-item"><a href="index.php?page=retorn&material_id=<?= $p->getMaterial()->getId() ?>">
            Retornar «<?= e($p->getMaterial()->getTitol()) ?>»</a> (<?= e($p->getUsuari()->nom) ?>)</li>
    <?php endforeach; ?>
    </ul>
    <?php
}
require 'views/partials/footer.php';
