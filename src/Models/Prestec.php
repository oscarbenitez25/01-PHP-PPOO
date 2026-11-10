<?php

namespace App\Models;

use DateTime;

/**
 * Préstec d'un material a un usuari. Calcula retards i multes.
 */
class Prestec
{
    private DateTime $dataPrestec;
    private ?DateTime $dataRetorn = null;

    public function __construct(
        private Material $material,
        private Usuari $usuari,
        private int $diesLimitPrestec = 14,
        ?DateTime $dataPrestec = null // Si és null, la data actual
    ) {
        $this->dataPrestec = $dataPrestec ?? new DateTime();
    }

    /** Tanca el préstec amb la data actual. */
    public function retornar(): void
    {
        $this->dataRetorn = new DateTime();
    }

    public function estaActiu(): bool
    {
        return $this->dataRetorn === null;
    }

    /** Dies transcorreguts des del préstec fins al retorn (o fins avui). */
    private function diesTranscorreguts(): int
    {
        $fi = $this->dataRetorn ?? new DateTime();
        return (int) $this->dataPrestec->diff($fi)->days;
    }

    /** Dies passats del límit (0 si no n'hi ha). */
    public function calcularDiesRetard(): int
    {
        return max(0, $this->diesTranscorreguts() - $this->diesLimitPrestec);
    }

    /** Delega en el material perquè cada tipus té la seva tarifa. */
    public function calcularMulta(): float
    {
        return $this->material->calcularMulta($this->calcularDiesRetard());
    }

    public function estaVencut(): bool
    {
        return $this->calcularDiesRetard() > 0;
    }

    /** Dies que queden fins al límit (negatiu si està vençut). */
    public function getDiesPendents(): int
    {
        $transcorreguts = (int) $this->dataPrestec->diff(new DateTime())->days;
        return $this->diesLimitPrestec - $transcorreguts;
    }

    public function getMaterial(): Material { return $this->material; }
    public function getUsuari(): Usuari { return $this->usuari; }
    public function getDataPrestec(): DateTime { return $this->dataPrestec; }
    public function getDataRetorn(): ?DateTime { return $this->dataRetorn; }
    public function getDiesLimitPrestec(): int { return $this->diesLimitPrestec; }
}
