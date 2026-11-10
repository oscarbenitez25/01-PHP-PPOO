<?php /** Vista d'error: mostra el missatge de l'excepció capturada ($error). */
$titol = 'Error'; require 'views/partials/header.php'; ?>
<h1>⚠️ No s'ha pogut completar l'operació</h1>
<div class="alert err"><?= e($error) ?></div>
<p><a class="btn" href="javascript:history.back()">Enrere</a> <a class="btn" href="index.php">Inici</a></p>
<?php require 'views/partials/footer.php'; ?>
