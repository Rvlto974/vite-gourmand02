<?php
class Database {
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $port;
    private static $pdo = null;

    public function __construct() {
        $this->host = $_ENV['DB_HOST'] ?? 'mysql';
        $this->dbname = $_ENV['DB_NAME'] ?? 'vite_gourmand';
        $this->username = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASS'] ?? 'root';
        $this->port = $_ENV['DB_PORT'] ?? '3306';
    }

    public function connect() {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4",
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_PERSISTENT => false
                    ]
                );
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}