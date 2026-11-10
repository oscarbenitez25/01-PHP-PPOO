<?php

namespace App\Interfaces;

/**
 * Contracte per als materials que es poden reservar (només Llibre i DVD).
 */
interface Reservable
{
    /** Reserva el material per a un usuari. Retorna false si ja està reservat. */
    public function reservar(string $nomUsuari): bool;

    /** Cancel·la la reserva actual. Retorna false si no n'hi ha cap. */
    public function cancelarReserva(): bool;

    /** Indica si el material està reservat. */
    public function estaReservat(): bool;

    /** Nom de l'usuari que ha fet la reserva, o null. */
    public function getUsuariReserva(): ?string;
}
