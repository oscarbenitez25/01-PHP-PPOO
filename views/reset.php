<?php
/** Destrueix la sessió (es regeneren les dades inicials) i torna a l'inici. */
$_SESSION = [];
session_destroy();
header('Location: index.php');
exit;
