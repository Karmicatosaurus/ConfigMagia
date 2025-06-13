<p align="center">
  <img src="https://www.karmicat.fr/imgs/logo_gr_magia.png" alt="Logo Magia" width="325">
</p>

# Fichiers de configuration de Magia

`ConfigBdd.php` est le fichier d'initialisation de la connexion à la base de données via PDO.

`ChargeDotenv.php` est le fichier d'initialisation du chargement des variables d’environnement via la bibliothèque `vlucas/phpdotenv`

## Prérequis

* PHP 8.2 minimum
  * extension MySQL pour PHP, si utilisation de MariaDB / MySQL
  * extension PostgreSql pour PHP si utilisation de PostgreSQL
* [Composer](https://getcomposer.org/)

## Fichier composer.json
```json
{
  "require": {
    "php": "^8.2",
    "vlucas/phpdotenv": "^5.5",
    "twig/twig": "^3.0",
    "ezyang/htmlpurifier": "^4.18"
  },  
  "autoload": {
    "psr-4": {
      "Magia\\": "src/"
    }
  }
}
```
## Fichier .env

```plain
# BASE DE DONNEES
SQL_PILOTE=votre_base_de_donnees (mysql / pgsql)
SQL_SERVEUR=localhost
SQL_BASE=nom_base_de_donnees
SQL_UTILISATEUR=utilisateur
SQL_PASSE=mot_de_passe
```
