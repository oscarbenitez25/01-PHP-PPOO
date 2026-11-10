<?php

namespace App\Models;

use App\Exceptions\MaterialNoDisponibleException;
use App\Exceptions\MaterialNoPrestatException;
use App\Exceptions\MaterialNoTrobatException;
use App\Exceptions\UsuariNoTrobatException;
use App\Interfaces\Reservable;
use BadMethodCallException;
use DateTime;

/**
 * Classe de gestió: materials, usuaris, préstecs i reserves.
 */
class Biblioteca
{
    private array $materials = []; // indexat per ID de material
    private array $usuaris = [];   // indexat per ID d'usuari
    private array $prestecs = [];  // tots els Prestec (actius i retornats)

    public function __construct(private string $nom) {}

    /**
     * Getters dinàmics sense definir els mètodes:
     * getLlibres/getRevistes/getDVDs, getMaterials, getUsuaris i getNom.
     */
    public function __call(string $nomMetode, array $arguments): mixed
    {
        return match ($nomMetode) {
            'getLlibres'   => $this->llistarPerTipus('Llibre'),
            'getRevistes'  => $this->llistarPerTipus('Revista'),
            'getDVDs'      => $this->llistarPerTipus('DVD'),
            'getMaterials' => $this->materials,
            'getUsuaris'   => $this->usuaris,
            'getNom'       => $this->nom,
            default        => throw new BadMethodCallException(
                "El mètode " . static::class . "::$nomMetode() no existeix"
            ),
        };
    }

    // ---------------- Materials ----------------

    public function afegirMaterial(Material $material): void
    {
        $this->materials[$material->getId()] = $material;
    }

    public function eliminarMaterial(int $id): bool
    {
        if (!isset($this->materials[$id])) {
            return false;
        }
        unset($this->materials[$id]);
        return true;
    }

    public function cercarPerId(int $id): ?Material
    {
        return $this->materials[$id] ?? null;
    }

    public function cercarPerTitol(string $titol): array
    {
        return array_values(array_filter(
            $this->materials,
            fn(Material $m) => stripos($m->getTitol(), $titol) !== false
        ));
    }

    public function cercarPerAutor(string $autor): array
    {
        return array_values(array_filter(
            $this->materials,
            fn(Material $m) => stripos($m->getAutor(), $autor) !== false
        ));
    }

    // ---------------- Llistats ----------------

    public function llistarDisponibles(): array
    {
        return array_values(array_filter($this->materials, fn(Material $m) => $m->isDisponible()));
    }

    public function llistarPrestats(): array
    {
        return array_values(array_filter($this->materials, fn(Material $m) => !$m->isDisponible()));
    }

    public function llistarPerTipus(string $tipus): array
    {
        return array_values(array_filter($this->materials, fn(Material $m) => $m->getTipus() === $tipus));
    }

    public function llistarPrestecsActius(): array
    {
        return array_values(array_filter($this->prestecs, fn(Prestec $p) => $p->estaActiu()));
    }

    /** Materials reservats (només els Reservable en poden tenir). */
    public function llistarReserves(): array
    {
        return array_values(array_filter(
            $this->materials,
            fn(Material $m) => $m instanceof Reservable && $m->estaReservat()
        ));
    }

    public function llistarPrestecsUsuari(int $usuariId): array
    {
        return array_values(array_filter(
            $this->prestecs,
            fn(Prestec $p) => $p->getUsuari()->id === $usuariId
        ));
    }

    // ---------------- Usuaris ----------------

    public function afegirUsuari(Usuari $usuari): void
    {
        $this->usuaris[$usuari->id] = $usuari;
    }

    /** Union type: int → cerca per ID; string → cerca per nom (sense majúscules). */
    public function cercarUsuari(int|string $identificador): ?Usuari
    {
        if (is_int($identificador)) {
            return $this->usuaris[$identificador] ?? null;
        }
        foreach ($this->usuaris as $usuari) {
            if (strcasecmp($usuari->nom, $identificador) === 0) {
                return $usuari;
            }
        }
        return null;
    }

    // ---------------- Préstecs i reserves ----------------

    /**
     * Crea un préstec. Si el material està reservat pel mateix usuari, es presta
     * i la reserva es cancel·la. $dataPrestec permet crear préstecs antics.
     */
    public function prestarMaterial(int $materialId, int $usuariId, ?DateTime $dataPrestec = null): Prestec
    {
        $material = $this->cercarPerId($materialId)
            ?? throw new MaterialNoTrobatException("No existeix cap material amb ID $materialId");
        $usuari = $this->cercarUsuari($usuariId)
            ?? throw new UsuariNoTrobatException("No existeix cap usuari amb ID $usuariId");

        if (!$material->isDisponible()) {
            throw new MaterialNoDisponibleException($materialId, "El material ja està prestat");
        }

        if ($material instanceof Reservable && $material->estaReservat()) {
            if ($material->getUsuariReserva() !== $usuari->nom) {
                throw new MaterialNoDisponibleException(
                    $materialId,
                    "El material està reservat per {$material->getUsuariReserva()}"
                );
            }
            $material->cancelarReserva(); // el mateix usuari el presta: la reserva es consumeix
        }

        $material->prestar($usuari->nom);
        $prestec = new Prestec($material, $usuari, 14, $dataPrestec);
        $this->prestecs[] = $prestec;
        $usuari->afegirPrestec($material);
        $usuari->afegirNotificacio("Préstec realitzat: {$material->getTitol()}");

        return $prestec;
    }

    /** Tanca el préstec actiu del material i retorna la multa (0 si no n'hi ha). */
    public function retornarMaterial(int $materialId): float
    {
        $material = $this->cercarPerId($materialId)
            ?? throw new MaterialNoTrobatException("No existeix cap material amb ID $materialId");

        $prestecActiu = null;
        foreach ($this->prestecs as $prestec) {
            if ($prestec->estaActiu() && $prestec->getMaterial()->getId() === $materialId) {
                $prestecActiu = $prestec;
                break;
            }
        }
        if ($prestecActiu === null) {
            throw new MaterialNoPrestatException("El material amb ID $materialId no estava prestat");
        }

        $prestecActiu->retornar();
        $multa = $prestecActiu->calcularMulta();
        $usuari = $prestecActiu->getUsuari();
        $material->retornar($usuari->nom);
        $usuari->eliminarPrestec($materialId);
        $usuari->afegirNotificacio(
            "Material retornat: {$material->getTitol()}" . ($multa > 0 ? sprintf(' (multa %.2f €)', $multa) : '')
        );

        return $multa;
    }

    /** Reserva un material per a un usuari. Retorna false si ja estava reservat. */
    public function reservarMaterial(int $materialId, int $usuariId): bool
    {
        $material = $this->cercarPerId($materialId)
            ?? throw new MaterialNoTrobatException("No existeix cap material amb ID $materialId");
        $usuari = $this->cercarUsuari($usuariId)
            ?? throw new UsuariNoTrobatException("No existeix cap usuari amb ID $usuariId");

        if (!$material instanceof Reservable) {
            throw new MaterialNoDisponibleException($materialId, "Les revistes no es poden reservar");
        }

        $ok = $material->reservar($usuari->nom);
        if ($ok) {
            $usuari->afegirNotificacio("Reserva realitzada: {$material->getTitol()}");
        }
        return $ok;
    }

    // ---------------- Estadístiques ----------------

    public function obtenirEstadistiques(): array
    {
        $perTipus = ['Llibre' => 0, 'Revista' => 0, 'DVD' => 0];
        foreach ($this->materials as $m) {
            $perTipus[$m->getTipus()]++;
        }

        $multes = 0.0;
        foreach ($this->llistarPrestecsActius() as $p) {
            $multes += $p->calcularMulta();
        }

        return [
            'total'          => count($this->materials),
            'disponibles'    => count($this->llistarDisponibles()),
            'prestats'       => count($this->llistarPrestats()),
            'reservats'      => count($this->llistarReserves()),
            'perTipus'       => $perTipus,
            'multesPendents' => $multes,
        ];
    }
}
