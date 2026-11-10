<?php

namespace App\Models;

use App\Traits\Auditoria;

/**
 * Classe abstracta base de tots els materials de la biblioteca.
 * Usa el trait Auditoria, així Llibre, Revista i DVD en tenen historial.
 */
abstract class Material
{
    use Auditoria;

    protected bool $disponible = true;

    public function __construct(
        protected int $id,
        protected string $titol,
        protected string $autor,
        protected int $anyPublicacio
    ) {}

    /** Multa en euros segons els dies de retard (cada tipus té la seva tarifa). */
    abstract public function calcularMulta(int $diesRetard): float;

    /** "Llibre", "Revista" o "DVD". */
    abstract public function getTipus(): string;

    /** Marca el material com a prestat. Retorna false si ja no estava disponible. */
    public function prestar(string $detalls = ''): bool
    {
        if (!$this->disponible) {
            return false;
        }
        $this->disponible = false;
        $this->registrarAccio('prestat', $detalls);
        return true;
    }

    /** Marca el material com a disponible un altre cop. */
    public function retornar(string $detalls = ''): void
    {
        $this->disponible = true;
        $this->registrarAccio('retornat', $detalls);
    }

    public function __toString(): string
    {
        $estat = $this->disponible ? 'Disponible' : 'Prestat';
        return "[{$this->getTipus()}] #{$this->id} - {$this->titol} ({$this->autor}, {$this->anyPublicacio}) - {$estat}";
    }

    public function getId(): int { return $this->id; }
    public function getTitol(): string { return $this->titol; }
    public function getAutor(): string { return $this->autor; }
    public function getAnyPublicacio(): int { return $this->anyPublicacio; }
    public function isDisponible(): bool { return $this->disponible; }
}
