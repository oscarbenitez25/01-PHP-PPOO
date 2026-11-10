<?php
$titol = 'Demo autoload';
// Classes, interfícies i traits de l'espai App\ que PSR-4 ha carregat en aquesta petició
$filtra = fn(array $noms) => array_values(array_filter($noms, fn($n) => str_starts_with($n, 'App\\')));
$grups = [
    'Classes'    => $filtra(get_declared_classes()),
    'Interfícies' => $filtra(get_declared_interfaces()),
    'Traits'     => $filtra(get_declared_traits()),
];
require 'views/partials/header.php';
?>
<h1>🔧 Demo · Autoload PSR-4</h1>
<p>Només s'han carregat les classes que aquesta petició ha necessitat (Composer les carrega sota demanda).</p>
<?php foreach ($grups as $nom => $llista): ?>
    <h2><?= e($nom) ?> (<?= count($llista) ?>)</h2>
    <ul class="list-group">
        <?php foreach ($llista as $c): ?><li class="list-item"><code><?= e($c) ?></code></li><?php endforeach; ?>
    </ul>
<?php endforeach; ?>
<?php require 'views/partials/footer.php'; ?>
