<?php $titol = 'Reserves'; require 'views/partials/header.php'; ?>
<h1>📝 Reserves actives</h1>
<table class="table">
    <thead><tr><th>ID</th><th>Tipus</th><th>Material</th><th>Reservat per</th><th>Estat</th></tr></thead>
    <tbody>
    <?php foreach ($biblioteca->llistarReserves() as $m): ?>
        <tr>
            <td><?= $m->getId() ?></td><td><?= e($m->getTipus()) ?></td>
            <td><a href="index.php?page=material&id=<?= $m->getId() ?>"><?= e($m->getTitol()) ?></a></td>
            <td><?= e($m->getUsuariReserva()) ?></td>
            <td><?= $m->isDisponible() ? 'Disponible' : 'Prestat' ?></td>
        </tr>
    <?php endforeach; ?>
    <?php if ($biblioteca->llistarReserves() === []): ?><tr><td colspan="5">No hi ha reserves actives.</td></tr><?php endif; ?>
    </tbody>
</table>
<p><a class="btn" href="index.php?page=reserva">Nova reserva</a></p>
<?php require 'views/partials/footer.php'; ?>
