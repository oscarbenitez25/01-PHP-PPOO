<?php

namespace App\Models;

use App\Interfaces\Reservable;
use App\Traits\Reserves;
use InvalidArgumentException;

/**
 * DVD: material reservable amb durada en minuts. Multa: 1,00 €/dia.
 */
class DVD extends Material implements Reservable
{
    use Reserves;

    private int $duracio; // en minuts

    public function __construct(
        int $id, string $titol, string $autor, int $anyPublicacio,
        int $duracio
    ) {
        parent::__construct($id, $titol, $autor, $anyPublicacio);
        $this->setDuracio($duracio);
    }

    public function calcularMulta(int $diesRetard): float
    {
        return 1.00 * max(0, $diesRetard);
    }

    public function getTipus(): string { return 'DVD'; }

    public function getDuracio(): int { return $this->duracio; }

    public function setDuracio(int $minuts): void
    {
        if ($minuts <= 0) {
            throw new InvalidArgumentException('La durada ha de ser major que 0');
        }
        $this->duracio = $minuts;
    }

    /** 150 → "2h 30min"; 45 → "0h 45min". */
    public function getDuracioFormatada(): string
    {
        return intdiv($this->duracio, 60) . 'h ' . ($this->duracio % 60) . 'min';
    }
}
