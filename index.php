<?php
/**
 * Únic punt d'entrada (router) de l'aplicació.
 * L'autoloader s'ha de carregar ABANS de session_start(): si no, PHP no sap
 * reconstruir els objectes guardats a la sessió.
 */
require_once __DIR__ . '/vendor/autoload.php';

use App\Data\DataGenerator;
use App\Exceptions\MaterialNoDisponibleException;

session_start();

if (!isset($_SESSION['biblioteca'])) {
    $_SESSION['biblioteca'] = DataGenerator::crear();
}
$biblioteca = $_SESSION['biblioteca'];

/** Escapa text per mostrar-lo de forma segura a l'HTML. */
function e(mixed $text): string
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

$page = $_GET['page'] ?? 'inici';

ob_start(); // així, si hi ha una excepció a mitja vista, no es mostra HTML a mitges
try {
    $vista = match ($page) {
        'inici'              => 'views/inici.php',
        'materials'          => 'views/materials.php',
        'material'           => 'views/material.php',
        'usuaris'            => 'views/usuaris.php',
        'usuari'             => 'views/usuari.php',
        'prestec'            => 'views/prestec.php',
        'retorn'             => 'views/retorn.php',
        'prestecs'           => 'views/prestecs.php',
        'reserva'            => 'views/reserva.php',
        'reserves'           => 'views/reserves.php',
        'estadistiques'      => 'views/estadistiques.php',
        'auditoria'          => 'views/auditoria.php',
        'demo_magic'         => 'views/demo_magic.php',
        'demo_serialization' => 'views/demo_serialization.php',
        'demo_exceptions'    => 'views/demo_exceptions.php',
        'demo_autoload'      => 'views/demo_autoload.php',
        'reset'              => 'views/reset.php',
        default              => 'views/404.php',
    };
    require $vista;
    ob_end_flush();
} catch (MaterialNoDisponibleException $e) {
    ob_end_clean();
    $error = (string) $e; // Usa el __toString() personalitzat
    require 'views/error.php';
} catch (Exception $e) {
    ob_end_clean();
    $error = $e->getMessage();
    require 'views/error.php';
} catch (Throwable $e) { // errors de tipus, etc.: l'usuari mai veu un Fatal error
    ob_end_clean();
    $error = 'Error intern: ' . $e->getMessage();
    require 'views/error.php';
}
