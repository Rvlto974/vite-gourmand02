-- Vite & Gourmand — Structure de la base de données
-- Générée le 2026-03-18
-- Ce script crée les tables, mais ne contient pas les données.
-- ATTENTION : DROP TABLE supprime la table et toutes les données qu'elle contient.

-- Désactive temporairement la vérification des clés étrangères
-- pour permettre la suppression des tables liées entre elles.
SET FOREIGN_KEY_CHECKS = 0;


-- Table des utilisateurs : clients, employés et administrateurs.
DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT, -- Identifiant unique de l'utilisateur
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL, -- Nom de famille
  `prenom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL, -- Prénom
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL, -- Adresse e-mail, unique
  `mot_de_passe` varchar(255) COLLATE utf8mb4_general_ci NOT NULL, -- Mot de passe haché
  `gsm` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL, -- Numéro de téléphone
  `adresse` text COLLATE utf8mb4_general_ci, -- Adresse de l'utilisateur
  `role` enum('visiteur','client','employe','admin') COLLATE utf8mb4_general_ci DEFAULT 'client', -- Rôle et droits
  `actif` tinyint(1) DEFAULT '1', -- Indique si le compte est actif
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP, -- Date de création du compte
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Table des menus proposés à la vente.
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int NOT NULL AUTO_INCREMENT, -- Identifiant unique du menu
  `titre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL, -- Nom du menu
  `description` text COLLATE utf8mb4_general_ci, -- Description du menu
  `theme` enum('Noel','Paques','classique','evenement') COLLATE utf8mb4_general_ci NOT NULL, -- Thème du menu
  `regime` enum('vegetarien','vegan','classique') COLLATE utf8mb4_general_ci NOT NULL, -- Type de régime
  `nb_personnes_min` int NOT NULL, -- Nombre minimal de personnes
  `prix_base` decimal(10,2) NOT NULL, -- Prix de base du menu
  `stock` int DEFAULT '0', -- Stock disponible
  `actif` tinyint(1) DEFAULT '1', -- Indique si le menu est proposé à la vente
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP, -- Date de création du menu
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL, -- Image principale du menu
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Table des plats composant les menus.
DROP TABLE IF EXISTS `plats`;
CREATE TABLE `plats` (
  `id` int NOT NULL AUTO_INCREMENT, -- Identifiant unique du plat
  `menu_id` int NOT NULL, -- Menu auquel le plat est rattaché
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL, -- Nom du plat
  `type` enum('entree','plat','dessert') COLLATE utf8mb4_general_ci NOT NULL, -- Catégorie du plat
  `description` text COLLATE utf8mb4_general_ci, -- Description du plat
  `allergenes` text COLLATE utf8mb4_general_ci, -- Allergènes éventuels
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `plats_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Table des images supplémentaires associées aux menus.
DROP TABLE IF EXISTS `menu_images`;
CREATE TABLE `menu_images` (
  `id` int NOT NULL AUTO_INCREMENT, -- Identifiant unique de l'image
  `menu_id` int NOT NULL, -- Menu auquel l'image est associée
  `url` varchar(500) COLLATE utf8mb4_general_ci NOT NULL, -- Chemin ou URL de l'image
  `ordre` int DEFAULT '0', -- Ordre d'affichage de l'image
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `menu_images_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Table des commandes passées par les utilisateurs.
DROP TABLE IF EXISTS `commandes`;
CREATE TABLE `commandes` (
  `id` int NOT NULL AUTO_INCREMENT, -- Identifiant unique de la commande
  `utilisateur_id` int NOT NULL, -- Utilisateur ayant passé la commande
  `menu_id` int NOT NULL, -- Menu commandé
  `nb_personnes` int NOT NULL, -- Nombre de personnes prévu
  `prix_total` decimal(10,2) NOT NULL, -- Prix total de la commande
  `statut` enum('nouvelle','acceptee','en_preparation','en_livraison','livree','terminee','attente_materiel','annulee') COLLATE utf8mb4_general_ci DEFAULT 'nouvelle', -- État actuel de la commande
  `adresse_livraison` text COLLATE utf8mb4_general_ci NOT NULL, -- Adresse de livraison
  `date_prestation` datetime NOT NULL, -- Date et heure prévues pour la prestation
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP, -- Date de création de la commande
  `heure_livraison` time DEFAULT NULL, -- Heure de livraison, si précisée
  `motif_annulation` text COLLATE utf8mb4_general_ci, -- Motif de l'annulation, le cas échéant
  `mode_contact` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL, -- Moyen de contact utilisé
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Historique des changements de statut des commandes.
DROP TABLE IF EXISTS `commande_statuts`;
CREATE TABLE `commande_statuts` (
  `id` int NOT NULL AUTO_INCREMENT, -- Identifiant de l'entrée d'historique
  `commande_id` int NOT NULL, -- Commande concernée
  `statut` varchar(50) COLLATE utf8mb4_general_ci NOT NULL, -- Statut enregistré
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP, -- Date du changement
  PRIMARY KEY (`id`),
  KEY `commande_id` (`commande_id`),
  CONSTRAINT `commande_statuts_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Table des avis laissés par les utilisateurs sur leurs commandes.
DROP TABLE IF EXISTS `avis`;
CREATE TABLE `avis` (
  `id` int NOT NULL AUTO_INCREMENT, -- Identifiant unique de l'avis
  `utilisateur_id` int NOT NULL, -- Auteur de l'avis
  `commande_id` int NOT NULL, -- Commande concernée
  `note` int DEFAULT NULL, -- Note de 1 à 5
  `commentaire` text COLLATE utf8mb4_general_ci, -- Texte de l'avis
  `valide` tinyint(1) DEFAULT '0', -- Indique si l'avis a été validé
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP, -- Date de création de l'avis
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `commande_id` (`commande_id`),
  CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`),
  CONSTRAINT `avis_chk_1` CHECK ((`note` between 1 and 5))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Horaires d'ouverture et jours de fermeture.
DROP TABLE IF EXISTS `horaires`;
CREATE TABLE `horaires` (
  `id` int NOT NULL AUTO_INCREMENT, -- Identifiant de l'horaire
  `jour` varchar(20) COLLATE utf8mb4_general_ci NOT NULL, -- Jour concerné
  `heure_ouverture` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL, -- Heure d'ouverture
  `heure_fermeture` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL, -- Heure de fermeture
  `ferme` tinyint(1) DEFAULT '0', -- Indique si l'établissement est fermé
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Réactive la vérification des clés étrangères.
SET FOREIGN_KEY_CHECKS = 1;