<?php

namespace App\Models;

use App\Exceptions\EmailInvalidException;
use DateTime;
use LogicException;

/**
 * Usuari de la biblioteca. Exposa dades de només lectura amb __get i
 * només deixa canviar l'email amb __set. No serialitza les notificacions.
 */
class Usuari
{
    private int $id;
    private string $nom;
    private string $email;
    private array $materialsPrestats = []; // objectes Material, indexats per ID
    private DateTime $dataRegistre;
    private array $notificacions = [];     // missatges temporals per a la web

    public function __construct(int $id, string $nom, string $email)
    {
        if (!$this->validarEmail($email)) {
            throw new EmailInvalidException("L'email '$email' no té un format vàlid");
        }
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->dataRegistre = new DateTime();
    }

    /** Lectura de propietats permeses; qualsevol altra retorna null. */
    public function __get(string $propietat): mixed
    {
        return match ($propietat) {
            'id'            => $this->id,
            'nom'           => $this->nom,
            'email'         => $this->email,
            'dataRegistre'  => $this->dataRegistre,
            default         => null,
        };
    }

    /** Només es pot modificar l'email (amb validació). */
    public function __set(string $propietat, mixed $valor): void
    {
        if ($propietat !== 'email') {
            throw new LogicException("No es pot modificar la propietat '$propietat'");
        }
        if (!is_string($valor) || !$this->validarEmail($valor)) {
            throw new EmailInvalidException("L'email '" . (is_scalar($valor) ? $valor : gettype($valor)) . "' no té un format vàlid");
        }
        $this->email = $valor;
    }

    /** Serialitza tot excepte $notificacions. */
    public function __sleep(): array
    {
        return ['id', 'nom', 'email', 'materialsPrestats', 'dataRegistre'];
    }

    /** En deserialitzar, les notificacions tornen a començar buides. */
    public function __wakeup(): void
    {
        $this->notificacions = [];
    }

    public function __toString(): string
    {
        $n = count($this->materialsPrestats);
        return "{$this->nom} <{$this->email}> - $n " . ($n === 1 ? 'préstec' : 'préstecs');
    }

    public function afegirPrestec(Material $material): void
    {
        $this->materialsPrestats[$material->getId()] = $material;
    }

    public function eliminarPrestec(int $materialId): void
    {
        unset($this->materialsPrestats[$materialId]);
    }

    public function getMaterialsPrestats(): array
    {
        return array_values($this->materialsPrestats);
    }

    public function getNumeroMaterialsPrestats(): int
    {
        return count($this->materialsPrestats);
    }

    public function afegirNotificacio(string $missatge): void
    {
        $this->notificacions[] = $missatge;
    }

    /** Retorna les notificacions i les buida. */
    public function obtenirNotificacions(): array
    {
        $n = $this->notificacions;
        $this->notificacions = [];
        return $n;
    }

    private function validarEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
