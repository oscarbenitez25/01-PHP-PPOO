# 📚 Sistema de Gestió de Biblioteca Digital

Projecte M0613-B2 (PHP orientat a objectes): gestió de biblioteca amb interfície web,
classes abstractes, interfícies, traits, mètodes màgics, excepcions pròpies,
PSR-4 amb Composer i persistència d'objectes a la sessió.

## Instal·lació
```bash
composer install
```

## Execució
```bash
php -S localhost:8000
```
Obre <http://localhost:8000/index.php>. Requereix PHP 8.0 o superior.

## URLs

| URL | Què fa |
|-----|--------|
| `index.php` | Dashboard amb el resum del sistema |
| `?page=materials` | Llistat de materials (`&type=llibre`, `revista` o `dvd` per filtrar) |
| `?page=material&id=X` | Detall d'un material i el seu historial d'auditoria |
| `?page=usuaris` | Llistat d'usuaris |
| `?page=usuari&id=X` | Perfil amb préstecs actius (`&action=prestecs` → historial) |
| `?page=prestec&material_id=X&usuari_id=Y` | Realitza un préstec (sense paràmetres: formulari) |
| `?page=retorn&material_id=X` | Retorna un material i mostra la multa |
| `?page=prestecs` | Préstecs actius, destacant els vençuts |
| `?page=reserva&material_id=X&usuari_id=Y` | Crea una reserva (sense paràmetres: formulari) |
| `?page=reserves` | Reserves actives |
| `?page=estadistiques` | Dashboard d'estadístiques |
| `?page=auditoria` | Historial d'accions de tots els materials |
| `?page=demo_magic` | `__toString`, `__call`, `__get`, `__set` (inclòs un `__set` no permès) |
| `?page=demo_serialization` | `serialize()` d'un usuari; comprova que `$notificacions` no es guarda |
| `?page=demo_exceptions` | Provoca i captura les 5 excepcions |
| `?page=demo_autoload` | Classes `App\` carregades per l'autoloader |
| `?page=reset` | Destrueix la sessió i regenera les dades inicials |

## Estructura
`index.php` (router amb `match`) · `views/` (una vista per pàgina + `partials/`) ·
`src/Models`, `Interfaces`, `Traits`, `Exceptions`, `Data` (namespaces `App\...`, PSR-4).

## Decisions de disseny i millores
- **Trait `Reserves`** (millora de «Per pensar»): el codi de reserves de `Llibre` i `DVD` s'escriu una sola vegada i es reutilitza amb `use Reserves;`.
- `prestar()` i `retornar()` de `Material` accepten un text opcional de detalls perquè l'auditoria indiqui quin usuari ha fet l'acció.
- `index.php` usa `ob_start()` per no mostrar HTML a mitges si es llança una excepció; també captura `Throwable`, així mai es veu un *Fatal error*.
- Les notificacions d'`Usuari` es mostren a la mateixa petició que les genera (no es serialitzen).
- Les pàgines de demo treballen amb objectes temporals per no alterar les dades de la sessió.
- Totes les sortides s'escapen amb `htmlspecialchars` (funció `e()`); tema clar/fosc automàtic.

## Vídeo de demostració
🎬 **Link:** _(afegeix aquí l'enllaç de YouTube no llistat o Google Drive)_
