<?php

namespace App\Models;

use App\Interfaces\Reservable;
use App\Traits\Reserves;
use InvalidArgumentException;

/**
 * Llibre: material reservable amb nombre de pàgines. Multa: 0,50 €/dia.
 */
class Llibre extends Material implements Reservable
{
    use Reserves; // implementa la interfície Reservable (compartit amb DVD)

    private int $numeroPagines;

    public function __construct(
        int $id, string $titol, string $autor, int $anyPublicacio,
        int $numeroPagines
    ) {
        parent::__construct($id, $titol, $autor, $anyPublicacio);
        $this->setNumeroPagines($numeroPagines); // El setter també valida
    }

    public function calcularMulta(int $diesRetard): float
    {
        return 0.50 * max(0, $diesRetard);
    }

    public function getTipus(): string { return 'Llibre'; }

    public function getNumeroPagines(): int { return $this->numeroPagines; }

    public function setNumeroPagines(int $pagines): void
    {
        if ($pagines <= 0) {
            throw new InvalidArgumentException('El nombre de pàgines ha de ser major que 0');
        }
        $this->numeroPagines = $pagines;
    }
}
