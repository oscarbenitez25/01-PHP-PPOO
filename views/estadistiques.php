<?php $titol = 'Estadístiques'; $s = $biblioteca->obtenirEstadistiques(); require 'views/partials/header.php'; ?>
<h1>📊 Estadístiques</h1>
<div class="stats-grid">
    <div class="stat-card"><h3>Total materials</h3><span class="number"><?= $s['total'] ?></span></div>
    <div class="stat-card"><h3>Disponibles</h3><span class="number"><?= $s['disponibles'] ?></span></div>
    <div class="stat-card"><h3>Prestats</h3><span class="number"><?= $s['prestats'] ?></span></div>
    <div class="stat-card"><h3>Reservats</h3><span class="number"><?= $s['reservats'] ?></span></div>
    <div class="stat-card"><h3>Multes pendents</h3><span class="number"><?= number_format($s['multesPendents'], 2) ?> €</span></div>
</div>
<h2>Per tipus</h2>
<div class="stats-grid">
    <?php foreach ($s['perTipus'] as $tipus => $n): ?>
        <div class="stat-card"><h3><?= e($tipus) ?></h3><span class="number"><?= $n ?></span></div>
    <?php endforeach; ?>
</div>
<?php require 'views/partials/footer.php'; ?>
