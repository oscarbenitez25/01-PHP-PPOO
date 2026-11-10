<?php $titol = 'Préstecs'; require 'views/partials/header.php'; ?>
<h1>🔄 Préstecs actius</h1>
<table class="table">
    <thead><tr><th>Material</th><th>Usuari</th><th>Data préstec</th><th>Estat</th><th>Multa</th><th>Accions</th></tr></thead>
    <tbody>
    <?php foreach ($biblioteca->llistarPrestecsActius() as $p): ?>
        <tr class="<?= $p->estaVencut() ? 'vencut' : '' ?>">
            <td><a href="index.php?page=material&id=<?= $p->getMaterial()->getId() ?>"><?= e($p->getMaterial()->getTitol()) ?></a></td>
            <td><a href="index.php?page=usuari&id=<?= $p->getUsuari()->id ?>"><?= e($p->getUsuari()->nom) ?></a></td>
            <td><?= $p->getDataPrestec()->format('d/m/Y') ?></td>
            <td><?php if ($p->estaVencut()): ?><span class="badge err">Vençut fa <?= $p->calcularDiesRetard() ?> dies</span>
                <?php else: ?><span class="badge ok">Queden <?= $p->getDiesPendents() ?> dies</span><?php endif; ?></td>
            <td><?= number_format($p->calcularMulta(), 2) ?> €</td>
            <td><a href="index.php?page=retorn&material_id=<?= $p->getMaterial()->getId() ?>">Retornar</a></td>
        </tr>
    <?php endforeach; ?>
    <?php if ($biblioteca->llistarPrestecsActius() === []): ?><tr><td colspan="6">No hi ha préstecs actius.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php require 'views/partials/footer.php'; ?>
