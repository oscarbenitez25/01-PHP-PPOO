<?php

namespace App\Exceptions;

use Exception;

/**
 * El material està prestat, reservat per un altre usuari, o no es pot reservar.
 * Guarda l'ID del material afectat i personalitza __toString().
 */
class MaterialNoDisponibleException extends Exception
{
    private int $materialId;

    public function __construct(int $materialId, string $message = "Material no disponible")
    {
        $this->materialId = $materialId;
        parent::__construct($message);
    }

    public function getMaterialId(): int
    {
        return $this->materialId;
    }

    public function __toString(): string
    {
        return "Error: el material amb ID {$this->materialId} no està disponible. {$this->getMessage()}";
    }
}
