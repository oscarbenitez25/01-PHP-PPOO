<?php http_response_code(404); $titol = 'No trobat'; require 'views/partials/header.php'; ?>
<h1>404 · Pàgina no trobada</h1>
<p>La pàgina «<?= e($_GET['page'] ?? '') ?>» no existeix. <a href="index.php">Torna a l'inici</a>.</p>
<?php require 'views/partials/footer.php'; ?>
