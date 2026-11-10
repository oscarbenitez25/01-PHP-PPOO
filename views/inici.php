<?php $titol = 'Inici'; require 'views/partials/header.php'; $s = $biblioteca->obtenirEstadistiques(); ?>
<h1>📚 <?= e($biblioteca->getNom()) ?></h1>
<p>Dashboard amb el resum del sistema.</p>
<div class="stats-grid">
    <div class="stat-card"><h3>Total materials</h3><span class="number"><?= $s['total'] ?></span></div>
    <div class="stat-card"><h3>Disponibles</h3><span class="number"><?= $s['disponibles'] ?></span></div>
    <div class="stat-card"><h3>Prestats</h3><span class="number"><?= $s['prestats'] ?></span></div>
    <div class="stat-card"><h3>Reservats</h3><span class="number"><?= $s['reservats'] ?></span></div>
    <div class="stat-card"><h3>Usuaris</h3><span class="number"><?= count($biblioteca->getUsuaris()) ?></span></div>
    <div class="stat-card"><h3>Multes pendents</h3><span class="number"><?= number_format($s['multesPendents'], 2) ?> €</span></div>
</div>
<h2>Accessos ràpids</h2>
<ul class="list-group">
    <li class="list-item"><a href="index.php?page=materials">Veure tots els materials</a></li>
    <li class="list-item"><a href="index.php?page=prestec">Realitzar un préstec</a></li>
    <li class="list-item"><a href="index.php?page=reserva">Fer una reserva</a></li>
    <li class="list-item"><a href="index.php?page=retorn">Retornar un material</a></li>
</ul>
<?php require 'views/partials/footer.php'; ?>
