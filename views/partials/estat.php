<?php
/** Mostra l'estat (Disponible/Prestat/Reservat) d'un material. Espera $m. */
use App\Interfaces\Reservable;
?>
<?php if ($m->isDisponible()): ?><span class="badge ok">Disponible</span><?php else: ?><span class="badge err">Prestat</span><?php endif; ?>
<?php if ($m instanceof Reservable && $m->estaReservat()): ?> <span class="badge warn">Reservat per <?= e($m->getUsuariReserva()) ?></span><?php endif; ?>
