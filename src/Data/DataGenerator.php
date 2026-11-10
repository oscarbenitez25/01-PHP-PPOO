<?php

namespace App\Data;

use App\Models\Biblioteca;
use App\Models\DVD;
use App\Models\Llibre;
use App\Models\Revista;
use App\Models\Usuari;
use DateTime;

/**
 * Genera una biblioteca amb dades d'exemple (10 materials, 4 usuaris,
 * 2 préstecs actius -un de vençut- i 1 reserva).
 */
class DataGenerator
{
    public static function crear(): Biblioteca
    {
        $b = new Biblioteca('Biblioteca Digital Municipal');

        // Llibres (ID 1-4)
        $b->afegirMaterial(new Llibre(1, 'El nom de la rosa', 'Umberto Eco', 1980, 502));
        $b->afegirMaterial(new Llibre(2, 'Cent anys de solitud', 'Gabriel García Márquez', 1967, 471));
        $b->afegirMaterial(new Llibre(3, 'Solitud', 'Víctor Català', 1905, 320));
        $b->afegirMaterial(new Llibre(4, '1984', 'George Orwell', 1949, 328));

        // Revistes (ID 5-7)
        $b->afegirMaterial(new Revista(5, 'National Geographic', 'National Geographic Society', 2024, 450));
        $b->afegirMaterial(new Revista(6, 'Sàpiens', 'Sàpiens Publicacions', 2023, 245));
        $b->afegirMaterial(new Revista(7, 'Time', 'Time USA', 2024, 12));

        // DVDs (ID 8-10)
        $b->afegirMaterial(new DVD(8, 'Inception', 'Christopher Nolan', 2010, 148));
        $b->afegirMaterial(new DVD(9, 'El Padrí', 'Francis Ford Coppola', 1972, 175));
        $b->afegirMaterial(new DVD(10, 'Pulp Fiction', 'Quentin Tarantino', 1994, 154));

        // Usuaris
        $b->afegirUsuari(new Usuari(1, 'Anna Puig', 'anna@mail.com'));
        $b->afegirUsuari(new Usuari(2, 'Joan Martí', 'joan@mail.com'));
        $b->afegirUsuari(new Usuari(3, 'Marta Vila', 'marta@mail.com'));
        $b->afegirUsuari(new Usuari(4, 'Pere Soler', 'pere@mail.com'));

        // Préstec vençut (fa 30 dies, límit 14) i préstec recent
        $b->prestarMaterial(1, 1, new DateTime('-30 days'));
        $b->prestarMaterial(8, 3, new DateTime('-2 days'));

        // Una reserva (DVD "El Padrí" reservat per Joan)
        $b->reservarMaterial(9, 2);

        // Les notificacions de la càrrega inicial no s'han de mostrar
        foreach ($b->getUsuaris() as $u) {
            $u->obtenirNotificacions();
        }

        return $b;
    }
}
