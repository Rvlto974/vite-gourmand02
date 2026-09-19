<?php
class Database {
    private $pdo;

    public function connect() {
        try {
            $this->pdo = new PDO(
                'sqlite:' . __DIR__ . '/../vite_gourmand.db',
                null,
                null,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            // Force UTF-8
            $this->pdo->exec("PRAGMA encoding='UTF-8'");
            return $this->pdo;
        } catch (PDOException $e) {
            die('Database connection error: ' . $e->getMessage());
        }
    }
}
?>
