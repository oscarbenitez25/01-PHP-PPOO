<?php $titol = 'Usuaris'; require 'views/partials/header.php'; ?>
<h1>👥 Usuaris</h1>
<table class="table">
    <thead><tr><th>ID</th><th>Nom</th><th>Email</th><th>Préstecs actius</th><th>Accions</th></tr></thead>
    <tbody>
    <?php foreach ($biblioteca->getUsuaris() as $u): ?>
        <tr>
            <td><?= $u->id ?></td>
            <td><?= e($u->nom) ?></td>
            <td><?= e($u->email) ?></td>
            <td><?= $u->getNumeroMaterialsPrestats() ?></td>
            <td><a href="index.php?page=usuari&id=<?= $u->id ?>">Perfil</a> ·
                <a href="index.php?page=usuari&id=<?= $u->id ?>&action=prestecs">Historial</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require 'views/partials/footer.php'; ?>
