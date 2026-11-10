<?php
/** Realitza un préstec (material_id + usuari_id) o mostra el formulari per triar-los. */
$materialId = isset($_GET['material_id']) ? (int) $_GET['material_id'] : null;
$usuariId   = isset($_GET['usuari_id']) ? (int) $_GET['usuari_id'] : null;
$titol = 'Préstec';

if ($materialId !== null && $usuariId !== null) {
    $prestec = $biblioteca->prestarMaterial($materialId, $usuariId); // pot llançar excepcions
    $usuari = $prestec->getUsuari();
    require 'views/partials/header.php';
    ?>
    <h1>🔄 Préstec</h1>
    <?php foreach ($usuari->obtenirNotificacions() as $n): ?><div class="alert ok"><?= e($n) ?></div><?php endforeach; ?>
    <ul class="list-group">
        <li class="list-item">Material: <?= e((string) $prestec->getMaterial()) ?></li>
        <li class="list-item">Usuari: <?= e((string) $usuari) ?></li>
        <li class="list-item">Data de retorn límit: <?= (clone $prestec->getDataPrestec())->modify('+' . $prestec->getDiesLimitPrestec() . ' days')->format('d/m/Y') ?></li>
    </ul>
    <p><a class="btn" href="index.php?page=usuari&id=<?= $usuari->id ?>">Veure perfil de <?= e($usuari->nom) ?></a></p>
    <?php
} else {
    require 'views/partials/header.php';
    ?>
    <h1>🔄 Realitzar un préstec</h1>
    <form method="get" action="index.php">
        <input type="hidden" name="page" value="prestec">
        <p><label>Material:
            <select name="material_id">
                <?php foreach ($biblioteca->llistarDisponibles() as $m): ?>
                    <option value="<?= $m->getId() ?>" <?= $m->getId() === $materialId ? 'selected' : '' ?>><?= e("#{$m->getId()} {$m->getTitol()} ({$m->getTipus()})") ?></option>
                <?php endforeach; ?>
            </select></label></p>
        <p><label>Usuari:
            <select name="usuari_id">
                <?php foreach ($biblioteca->getUsuaris() as $u): ?>
                    <option value="<?= $u->id ?>" <?= $u->id === $usuariId ? 'selected' : '' ?>><?= e($u->nom) ?></option>
                <?php endforeach; ?>
            </select></label></p>
        <button type="submit">Prestar</button>
    </form>
    <?php
}
require 'views/partials/footer.php';
