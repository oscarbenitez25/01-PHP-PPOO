<?php
use App\Data\DataGenerator;
use App\Exceptions\EmailInvalidException;
use App\Exceptions\MaterialNoDisponibleException;
use App\Exceptions\MaterialNoPrestatException;
use App\Exceptions\MaterialNoTrobatException;
use App\Exceptions\UsuariNoTrobatException;
use App\Models\Usuari;

$titol = 'Demo excepcions';
$tmp = DataGenerator::crear(); // biblioteca temporal: no modifica la de la sessió

/** Executa una acció, captura l'excepció i retorna [classe, missatge]. */
$provoca = function (callable $accio): array {
    try {
        $accio();
        return ['(cap excepció)', ''];
    } catch (MaterialNoTrobatException $e) {
        return ['MaterialNoTrobatException', $e->getMessage()];
    } catch (MaterialNoDisponibleException $e) {
        return ['MaterialNoDisponibleException', (string) $e . ' [getMaterialId() = ' . $e->getMaterialId() . ']'];
    } catch (MaterialNoPrestatException $e) {
        return ['MaterialNoPrestatException', $e->getMessage()];
    } catch (UsuariNoTrobatException $e) {
        return ['UsuariNoTrobatException', $e->getMessage()];
    } catch (EmailInvalidException $e) {
        return ['EmailInvalidException', $e->getMessage()];
    } catch (Exception $e) { // sempre l'última: la més general
        return ['Exception', $e->getMessage()];
    }
};

$proves = [
    'prestarMaterial(999, 1)'            => fn() => $tmp->prestarMaterial(999, 1),
    'reservarMaterial(5, 1) · revista'   => fn() => $tmp->reservarMaterial(5, 1),
    'retornarMaterial(2) · no prestat'   => fn() => $tmp->retornarMaterial(2),
    'prestarMaterial(2, 999)'            => fn() => $tmp->prestarMaterial(2, 999),
    'new Usuari(5, "X", "email-malament")' => fn() => new Usuari(5, 'X', 'email-malament'),
];

require 'views/partials/header.php';
?>
<h1>🔧 Demo · Excepcions</h1>
<table class="table">
    <thead><tr><th>Codi</th><th>Excepció capturada</th><th>Missatge</th></tr></thead>
    <tbody>
    <?php foreach ($proves as $codi => $accio): [$classe, $missatge] = $provoca($accio); ?>
        <tr><td><code><?= e($codi) ?></code></td><td><?= e($classe) ?></td><td><?= e($missatge) ?></td></tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require 'views/partials/footer.php'; ?>
