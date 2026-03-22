<?php
$pdo = new PDO('mysql:host=efr0nz.h.filess.io;port=3307;dbname=vite_gourmand_raiseevery', 'vite_gourmand_raiseevery', 'Mat97460!');
$pdo->exec('CREATE TABLE IF NOT EXISTS commande_statuts (id INT AUTO_INCREMENT PRIMARY KEY, commande_id INT NOT NULL, statut VARCHAR(50) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (commande_id) REFERENCES commandes(id))');
echo 'OK';