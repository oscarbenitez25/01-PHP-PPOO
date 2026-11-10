<?php

namespace App\Models;

use InvalidArgumentException;

/**
 * Revista: NO és reservable. Multa: 0,25 €/dia.
 */
class Revista extends Material
{
    private int $numeroEdicio;

    public function __construct(
        int $id, string $titol, string $autor, int $anyPublicacio,
        int $numeroEdicio
    ) {
        parent::__construct($id, $titol, $autor, $anyPublicacio);
        $this->setNumeroEdicio($numeroEdicio);
    }

    public function calcularMulta(int $diesRetard): float
    {
        return 0.25 * max(0, $diesRetard);
    }

    public function getTipus(): string { return 'Revista'; }

    public function getNumeroEdicio(): int { return $this->numeroEdicio; }

    public function setNumeroEdicio(int $numero): void
    {
        if ($numero <= 0) {
            throw new InvalidArgumentException("El número d'edició ha de ser major que 0");
        }
        $this->numeroEdicio = $numero;
    }
}
