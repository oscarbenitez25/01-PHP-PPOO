<?php

namespace App\Traits;

/**
 * MILLORA ("Per pensar"): codi de reserves compartit per Llibre i DVD.
 * Implementa els mètodes de la interfície Reservable una sola vegada.
 * Necessita que la classe que l'usa tingui registrarAccio() (trait Auditoria).
 */
trait Reserves
{
    private ?string $usuariReserva = null;

    public function reservar(string $nomUsuari): bool
    {
        if ($this->usuariReserva !== null) {
            return false;
        }
        $this->usuariReserva = $nomUsuari;
        $this->registrarAccio('reservat', $nomUsuari);
        return true;
    }

    public function cancelarReserva(): bool
    {
        if ($this->usuariReserva === null) {
            return false;
        }
        $this->registrarAccio('reserva_cancel·lada', $this->usuariReserva);
        $this->usuariReserva = null;
        return true;
    }

    public function estaReservat(): bool
    {
        return $this->usuariReserva !== null;
    }

    public function getUsuariReserva(): ?string
    {
        return $this->usuariReserva;
    }
}
