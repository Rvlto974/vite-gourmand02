<?php

// Gère la connexion à la base de données avec PDO.
class Database
{
    // Paramètres de connexion à la base de données.
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $port;

    // Conserve une connexion PDO partagée pour éviter d’en créer plusieurs.
    private static $pdo = null;

    // Charge les paramètres de connexion depuis les variables d’environnement.
    // Des valeurs par défaut sont utilisées si elles ne sont pas définies.
    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'] ?? 'mysql';
        $this->dbname = $_ENV['DB_NAME'] ?? 'vite_gourmand';
        $this->username = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASS'] ?? 'root';
        $this->port = $_ENV['DB_PORT'] ?? '3306';
    }

    // Établit la connexion PDO si elle n’existe pas encore,
    // puis retourne cette connexion.
    public function connect()
    {
        if (self::$pdo === null) {
            try {
                // Crée la connexion MySQL en utilisant l’encodage utf8mb4.
                self::$pdo = new PDO(
                    "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4",
                    $this->username,
                    $this->password,
                    [
                        // Déclenche une exception en cas d’erreur PDO.
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

                        // Désactive les connexions persistantes.
                        PDO::ATTR_PERSISTENT => false
                    ]
                );
            } catch (PDOException $e) {
                // Arrête le script si la connexion échoue.
                die("Erreur de connexion : " . $e->getMessage());
            }
        }

        // Retourne la connexion existante ou celle qui vient d’être créée.
        return self::$pdo;
    }
}