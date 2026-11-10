<?php
$titol = 'Demo serialització';
$u = $biblioteca->cercarUsuari(1);
$u->afegirNotificacio('Missatge temporal que NO s\'ha de guardar');

$serialitzat = serialize($u);              // crida __sleep()
$restaurat = unserialize($serialitzat);    // crida __wakeup()

// Les propietats privades porten caràcters nuls: els fem visibles
$visible = str_replace("\0", '·', $serialitzat);
$contéNotificacions = str_contains($serialitzat, 'notificacions');
$notifRestaurades = $restaurat->obtenirNotificacions();
$u->obtenirNotificacions(); // netegem la notificació de prova de l'usuari real

require 'views/partials/header.php';
?>
<h1>🔧 Demo · Serialització</h1>
<p>Usuari: <code><?= e((string) $u) ?></code></p>
<h2>serialize($usuari)</h2>
<pre><?= e($visible) ?></pre>
<ul class="list-group">
    <li class="list-item">Conté «notificacions»? <?= $contéNotificacions
        ? '<span class="badge err">SÍ (error)</span>' : '<span class="badge ok">NO ✔ (excloses per __sleep)</span>' ?></li>
    <li class="list-item">Notificacions després d'unserialize() (__wakeup): <strong><?= count($notifRestaurades) ?></strong> (array buit)</li>
    <li class="list-item">Usuari restaurat: <code><?= e((string) $restaurat) ?></code></li>
</ul>
<?php require 'views/partials/footer.php'; ?>
