<?php
class Database {
    private $pdo;

    public function connect() {
        try {
            // Chemin absolu depuis /var/www/html
            $dbPath = '/var/www/html/vite_gourmand.db';
            
            $this->pdo = new PDO(
                'sqlite:' . $dbPath,
                null,
                null,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            return $this->pdo;
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
}
