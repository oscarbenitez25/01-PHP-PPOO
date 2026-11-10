<?php
use App\Exceptions\UsuariNoTrobatException;

$id = (int) ($_GET['id'] ?? 0);
$u = $biblioteca->cercarUsuari($id) ?? throw new UsuariNoTrobatException("No existeix cap usuari amb ID $id");
$historial = ($_GET['action'] ?? '') === 'prestecs'; // true: historial complet
$prestecs = $biblioteca->llistarPrestecsUsuari($id);
if (!$historial) {
    $prestecs = array_values(array_filter($prestecs, fn($p) => $p->estaActiu()));
}
$titol = $u->nom;
require 'views/partials/header.php';
?>
<h1>👤 <?= e($u->nom) ?></h1>
<ul class="list-group">
    <li class="list-item">Resum: <code><?= e((string) $u) ?></code></li>
    <li class="list-item">ID: <?= $u->id ?></li>
    <li class="list-item">Email: <?= e($u->email) ?></li>
    <li class="list-item">Data de registre: <?= $u->dataRegistre->format('d/m/Y H:i') ?></li>
</ul>
<h2><?= $historial ? 'Historial de préstecs' : 'Préstecs actius' ?></h2>
<p>
    <a class="btn" href="index.php?page=usuari&id=<?= $u->id ?>">Actius</a>
    <a class="btn" href="index.php?page=usuari&id=<?= $u->id ?>&action=prestecs">Historial</a>
    <a class="btn" href="index.php?page=prestec&usuari_id=<?= $u->id ?>">Nou préstec</a>
</p>
<table class="table">
    <thead><tr><th>Material</th><th>Data préstec</th><th>Data retorn</th><th>Estat</th><th>Multa</th></tr></thead>
    <tbody>
    <?php foreach ($prestecs as $p): ?>
        <tr class="<?= $p->estaActiu() && $p->estaVencut() ? 'vencut' : '' ?>">
            <td><a href="index.php?page=material&id=<?= $p->getMaterial()->getId() ?>"><?= e($p->getMaterial()->getTitol()) ?></a></td>
            <td><?= $p->getDataPrestec()->format('d/m/Y') ?></td>
            <td><?= $p->getDataRetorn()?->format('d/m/Y') ?? '—' ?></td>
            <td><?php if (!$p->estaActiu()): ?><span class="badge ok">Retornat</span>
                <?php elseif ($p->estaVencut()): ?><span class="badge err">Vençut (<?= $p->calcularDiesRetard() ?> dies)</span>
                <?php else: ?><span class="badge warn">Actiu (<?= $p->getDiesPendents() ?> dies)</span><?php endif; ?></td>
            <td><?= number_format($p->calcularMulta(), 2) ?> €</td>
        </tr>
    <?php endforeach; ?>
    <?php if ($prestecs === []): ?><tr><td colspan="5">Cap préstec.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php require 'views/partials/footer.php'; ?>
