-- Création de la base de données
CREATE DATABASE IF NOT EXISTS vite_gourmand;
USE vite_gourmand;

-- Table utilisateurs
CREATE TABLE utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    gsm VARCHAR(20),
    adresse TEXT,
    role ENUM('visiteur', 'client', 'employe', 'admin') DEFAULT 'client',
    actif BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table menus
CREATE TABLE menus (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    theme ENUM('Noel', 'Paques', 'classique', 'evenement') NOT NULL,
    regime ENUM('vegetarien', 'vegan', 'classique') NOT NULL,
    nb_personnes_min INT NOT NULL,
    prix_base DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    actif BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table plats
CREATE TABLE plats (
    id INT PRIMARY KEY AUTO_INCREMENT,
    menu_id INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    type ENUM('entree', 'plat', 'dessert') NOT NULL,
    description TEXT,
    allergenes TEXT,
    FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
);

-- Table images menus
CREATE TABLE menu_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    menu_id INT NOT NULL,
    url VARCHAR(500) NOT NULL,
    principale BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
);

-- Table commandes
CREATE TABLE commandes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    menu_id INT NOT NULL,
    nb_personnes INT NOT NULL,
    prix_total DECIMAL(10,2) NOT NULL,
    statut ENUM('nouvelle', 'acceptee', 'en_preparation', 'en_livraison', 'livree', 'attente_materiel', 'terminee') DEFAULT 'nouvelle',
    adresse_livraison TEXT NOT NULL,
    date_prestation DATETIME NOT NULL,
    heure_livraison TIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (menu_id) REFERENCES menus(id)
);

-- Table avis
CREATE TABLE avis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    commande_id INT NOT NULL,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    valide BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (commande_id) REFERENCES commandes(id)
);