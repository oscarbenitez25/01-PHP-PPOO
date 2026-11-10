<?php

namespace App\Traits;

/**
 * Trait que dota una classe d'un historial d'accions (auditoria).
 */
trait Auditoria
{
    private array $historial = [];

    /** Afegeix una entrada a l'historial amb la data actual. */
    public function registrarAccio(string $accio, string $detalls = ''): void
    {
        $this->historial[] = [
            'data'    => date('Y-m-d H:i:s'),
            'accio'   => $accio,
            'detalls' => $detalls,
        ];
    }

    /** Retorna tot l'historial. */
    public function obtenirHistorial(): array
    {
        return $this->historial;
    }

    /** Retorna l'última acció registrada, o null si no n'hi ha cap. */
    public function obtenirUltimaAccio(): ?array
    {
        return $this->historial === [] ? null : $this->historial[array_key_last($this->historial)];
    }

    /** Buida l'historial. */
    public function netejarHistorial(): void
    {
        $this->historial = [];
    }
}
