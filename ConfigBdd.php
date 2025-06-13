<?php

namespace Magia\Config;

use PDO;
use PDOException;
use PDOStatement;
use InvalidArgumentException;

use Magia\Config\ChargeDotenv;

// Chargement des variables d'environnement
ChargeDotenv::charge();

class ConfigBdd
{
    private static ?self $instance = null;
    private PDO $connexion_pdo;

    private function __construct()
    {
        $this->initialiseConnexionPdo();
    }

    public static function charge(): self
    {
        return self::$instance ??= new self();
    }

    private function initialiseConnexionPdo(): PDO
    {
        $pilote_sql     = $_ENV['SQL_PILOTE'] ?? '';
        $serveur        = $_ENV['SQL_SERVEUR'] ?? 'localhost';
        $port           = $_ENV['SQL_PORT'] ?? ($pilote_sql === 'pgsql' ? 5432 : 3306);
        $base           = $_ENV['SQL_BASE'] ?? '';
        $utilisateur    = $_ENV['SQL_UTILISATEUR'] ?? '';
        $passe          = $_ENV['SQL_PASSE'] ?? '';

        try {

            if(empty($base) || empty($utilisateur) || empty($passe))
            {
                throw new InvalidArgumentException("Les informations de connexion à la base de données sont incomplètes.");
            }

            $source_bdd = match($pilote_sql) {
                'mysql'   => "mysql:host=$serveur;port=$port;dbname=$base;charset=utf8mb4",
                'pgsql'   => "pgsql:host=$serveur;port=$port;dbname=$base",
                default => throw new InvalidArgumentException("Moteur de base de données $pilote_sql non pris en charge."),
            };

            $this->connexion_pdo = new PDO($source_bdd, $utilisateur, $passe, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);

            return $this->connexion_pdo;
        } catch (PDOException $e) {
            throw new PDOException("Une erreur PDO est survenue : " . $e->getMessage());
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException("Une erreur est survenue : " . $e->getMessage());
        }
    }

    public function requete(string $requeteSql, array $parametres = []): PDOStatement
    {
        $requete = $this->connexion_pdo->prepare($requeteSql);
        $requete->execute($parametres);
        return $requete;
    }

    public function execute(string $requeteSql, array $parametres = []): int
    {
        return $this->requete($requeteSql,$parametres)->rowCount();
    }

    public function recupere(string $requeteSql, array $parametres = []): ?array
    {
        return $this->requete($requeteSql,$parametres)->fetch();
    }

    public function recupereTout(string $requeteSql, array $parametres = []): array
    {
        return $this->requete($requeteSql,$parametres)->fetchAll();
    }    
}