<?php
/** Crea una reserva (material_id + usuari_id) o mostra el formulari. */
$titol = 'Reserva';
$materialId = isset($_GET['material_id']) ? (int) $_GET['material_id'] : null;
$usuariId   = isset($_GET['usuari_id']) ? (int) $_GET['usuari_id'] : null;

if ($materialId !== null && $usuariId !== null) {
    $ok = $biblioteca->reservarMaterial($materialId, $usuariId); // pot llançar excepcions
    $material = $biblioteca->cercarPerId($materialId);
    $usuari = $biblioteca->cercarUsuari($usuariId);
    require 'views/partials/header.php';
    ?>
    <h1>📝 Reserva</h1>
    <?php if ($ok): ?>
        <?php foreach ($usuari->obtenirNotificacions() as $n): ?><div class="alert ok"><?= e($n) ?></div><?php endforeach; ?>
        <p><?= e($material->getTitol()) ?> reservat per <?= e($usuari->nom) ?>.</p>
    <?php else: ?>
        <div class="alert warn">No s'ha pogut reservar: «<?= e($material->getTitol()) ?>» ja està reservat per <?= e($material->getUsuariReserva()) ?>.</div>
    <?php endif; ?>
    <p><a class="btn" href="index.php?page=reserves">Veure reserves</a></p>
    <?php
} else {
    require 'views/partials/header.php';
    ?>
    <h1>📝 Crear una reserva</h1>
    <form method="get" action="index.php">
        <input type="hidden" name="page" value="reserva">
        <p><label>Material:
            <select name="material_id">
                <?php foreach ($biblioteca->getMaterials() as $m): ?>
                    <option value="<?= $m->getId() ?>" <?= $m->getId() === $materialId ? 'selected' : '' ?>><?= e("#{$m->getId()} {$m->getTitol()} ({$m->getTipus()})") ?></option>
                <?php endforeach; ?>
            </select></label></p>
        <p><label>Usuari:
            <select name="usuari_id">
                <?php foreach ($biblioteca->getUsuaris() as $u): ?>
                    <option value="<?= $u->id ?>"><?= e($u->nom) ?></option>
                <?php endforeach; ?>
            </select></label></p>
        <button type="submit">Reservar</button>
    </form>
    <?php
}
require 'views/partials/footer.php';
