<?php
class Database {
    private $dbfile;
    private static $pdo = null;

    public function __construct() {
        $env = $_ENV['APP_ENV'] ?? 'development';
        
        if ($env === 'production') {
            $this->dbfile = '/data/vite_gourmand.sqlite';
        } else {
            $this->dbfile = __DIR__ . '/../storage/database.sqlite';
        }
    }

    public function connect() {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        try {
            if (!file_exists($this->dbfile)) {
                touch($this->dbfile);
                chmod($this->dbfile, 0666);
            }

            $dsn = "sqlite:{$this->dbfile}";
            self::$pdo = new PDO($dsn);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            self::$pdo->exec('PRAGMA foreign_keys = ON');
            
            return self::$pdo;
        } catch (PDOException $e) {
            die('Erreur de connexion : ' . $e->getMessage());
        }
    }

    public function disconnect() {
        self::$pdo = null;
    }
}
?>
