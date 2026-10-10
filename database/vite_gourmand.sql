-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Oct 10, 2026 at 05:10 AM
-- Server version: 8.0.46
-- PHP Version: 8.3.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vite_gourmand`
--

-- --------------------------------------------------------

--
-- Table structure for table `avis`
--

CREATE TABLE `avis` (
  `id` int NOT NULL,
  `utilisateur_id` int NOT NULL,
  `commande_id` int NOT NULL,
  `note` int DEFAULT NULL,
  `commentaire` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `valide` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `avis`
--

INSERT INTO `avis` (`id`, `utilisateur_id`, `commande_id`, `note`, `commentaire`, `valide`, `created_at`) VALUES
(1, 12, 43, 4, 'Trés bon !!!', 1, '2026-10-05 12:11:13');

-- --------------------------------------------------------

--
-- Table structure for table `commandes`
--

CREATE TABLE `commandes` (
  `id` int NOT NULL,
  `utilisateur_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `nb_personnes` int NOT NULL,
  `prix_total` decimal(10,2) NOT NULL,
  `statut` enum('nouvelle','acceptee','en_preparation','en_livraison','livree','terminee','attente_materiel','annulee') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'nouvelle',
  `adresse_livraison` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_prestation` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `heure_livraison` time DEFAULT NULL,
  `motif_annulation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `mode_contact` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commandes`
--

INSERT INTO `commandes` (`id`, `utilisateur_id`, `menu_id`, `nb_personnes`, `prix_total`, `statut`, `adresse_livraison`, `date_prestation`, `created_at`, `heure_livraison`, `motif_annulation`, `mode_contact`) VALUES
(42, 1, 4, 6, 287.95, 'terminee', '21 rue des goyaves\r\napt 10 residence quartier latin', '2026-10-15 12:48:00', '2026-10-05 08:49:00', NULL, NULL, NULL),
(43, 12, 2, 6, 290.90, 'terminee', '21 rue des goyaves\r\napt 10 residence quartier latin', '2026-10-07 16:01:00', '2026-10-05 12:01:39', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `commande_statuts`
--

CREATE TABLE `commande_statuts` (
  `id` int NOT NULL,
  `commande_id` int NOT NULL,
  `statut` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commande_statuts`
--

INSERT INTO `commande_statuts` (`id`, `commande_id`, `statut`, `created_at`) VALUES
(27, 42, 'nouvelle', '2026-10-05 08:49:00'),
(28, 42, 'acceptee', '2026-10-05 11:52:56'),
(29, 42, 'en_preparation', '2026-10-05 11:54:14'),
(30, 42, 'en_livraison', '2026-10-05 11:54:20'),
(31, 42, 'livree', '2026-10-05 11:54:26'),
(32, 42, 'terminee', '2026-10-05 11:54:31'),
(33, 43, 'nouvelle', '2026-10-05 12:01:39'),
(34, 43, 'terminee', '2026-10-05 12:10:36');

-- --------------------------------------------------------

--
-- Table structure for table `horaires`
--

CREATE TABLE `horaires` (
  `id` int NOT NULL,
  `jour` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `heure_ouverture` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `heure_fermeture` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ferme` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `horaires`
--

INSERT INTO `horaires` (`id`, `jour`, `heure_ouverture`, `heure_fermeture`, `ferme`) VALUES
(1, 'Lundi', '10:00', '18:00', 0),
(2, 'Mardi', '09:00', '18:00', 0),
(3, 'Mercredi', '09:00', '18:00', 0),
(4, 'Jeudi', '09:00', '18:00', 0),
(5, 'Vendredi', '09:00', '18:00', 0),
(6, 'Samedi', '09:00', '12:00', 0),
(7, 'Dimanche', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `theme` enum('Noel','Paques','classique','evenement') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `regime` enum('vegetarien','vegan','classique') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nb_personnes_min` int NOT NULL,
  `prix_base` decimal(10,2) NOT NULL,
  `stock` int DEFAULT '0',
  `actif` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `conditions` text COLLATE utf8mb4_general_ci COMMENT 'Délais de commande, précautions de stockage, etc.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `titre`, `description`, `theme`, `regime`, `nb_personnes_min`, `prix_base`, `stock`, `actif`, `created_at`, `image`, `conditions`) VALUES
(1, 'Menu Noel Premium', 'Un menu festif et raffiné pour célébrer Noël en famille.\r\nAu programme : Foie Gras Maison, Roulés feuilletés à la saucisse, Pigeons rôtis aux morilles.\r\nEt pour finir : une Bûche de Noël maison au chocolat.', 'Noel', 'classique', 8, 320.00, 10, 1, '2026-02-25 02:57:15', '/assets/images/menus/menu-1-noel.jpg', NULL),
(2, 'Menu Paques Tradition', 'Célébrez Pâques avec tradition et saveurs.\r\nAu programme : une Terrine de saumon maison,\r\nun Gigot d’agneau au miel et aux épices fondant à souhait,\r\nune Tarte aux fraises de saison pour terminer.', 'Paques', 'classique', 6, 280.00, 7, 1, '2026-02-25 02:57:15', '/assets/images/menus/menu-2-paques.jpeg', NULL),
(3, 'Menu Vegetal Printemps', 'Un menu 100 % végétarien aux saveurs du printemps.\r\nUn Velouté de petits pois à la menthe fraîche,\r\nune Tarte rustique asperges et petits pois dorée à souhait,\r\net un Fraisier léger à la crème fouettée pour finir en douceur.', 'classique', 'vegetarien', 4, 200.00, 5, 1, '2026-02-25 02:57:15', '/assets/images/menus/menu-3-printemps.jpeg', NULL),
(4, 'Menu Boeuf Bourguignon', 'Un menu classique et généreux pour les amateurs de cuisine française.\r\nUne Soupe à l’oignon gratinée pour commencer,\r\nun Bœuf Bourguignon mijoté au vin rouge fondant et savoureux,\r\net une Tarte Tatin aux pommes caramélisées pour terminer en gourmandise.', 'classique', 'classique', 6, 280.00, 8, 1, '2026-02-28 02:59:44', '/assets/images/menus/menu-4-boeuf.jpeg', NULL),
(5, 'Menu Poulet Basquaise', 'Un menu ensoleillé aux saveurs du Sud-Ouest.\r\nUne Salade de tomates et poivrons grillés pour ouvrir l’appétit,\r\nun Poulet Basquaise aux poivrons et pommes de terre mijoté avec soin,\r\net un Gâteau Basque à la crème pâtissière pour finir en douceur.', 'classique', 'classique', 6, 250.00, 8, 1, '2026-02-28 02:59:44', '/assets/images/menus/menu-5-poulet.jpg', NULL),
(6, 'Menu Magret de Canard', 'Un menu raffiné aux saveurs du Périgord.\r\nUn Velouté de butternut au lard croustillant pour commencer,\r\nun Magret de canard aux pommes de terre sarladaises cuit à la perfection,\r\net un Fondant au chocolat noir coulant pour terminer en gourmandise.', 'classique', 'classique', 6, 300.00, 4, 1, '2026-02-28 02:59:44', '/assets/images/menus/menu-6-canard.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_images`
--

CREATE TABLE `menu_images` (
  `id` int NOT NULL,
  `menu_id` int NOT NULL,
  `url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ordre` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_images`
--

INSERT INTO `menu_images` (`id`, `menu_id`, `url`, `ordre`) VALUES
(5, 1, 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800', 0),
(6, 1, 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=800', 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu_plats`
--

CREATE TABLE `menu_plats` (
  `menu_id` int NOT NULL,
  `plat_id` int NOT NULL,
  `ordre` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_plats`
--

INSERT INTO `menu_plats` (`menu_id`, `plat_id`, `ordre`) VALUES
(3, 248, 0),
(3, 249, 0),
(3, 250, 0),
(4, 244, 0),
(4, 245, 0),
(4, 247, 0),
(5, 238, 0),
(5, 239, 0),
(5, 240, 0),
(6, 241, 0),
(6, 242, 0),
(6, 243, 0);

-- --------------------------------------------------------

--
-- Table structure for table `plats`
--

CREATE TABLE `plats` (
  `id` int NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('entree','plat','dessert') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `allergenes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plats`
--

INSERT INTO `plats` (`id`, `nom`, `type`, `description`, `allergenes`) VALUES
(238, 'Salade tomates poivrons', 'entree', 'Salade de tomates et poivrons grillés', 'Aucun'),
(239, 'Poulet Basquaise', 'plat', 'Poulet Basquaise aux poivrons et pommes de terre', 'Aucun'),
(240, 'Gâteau Basque', 'dessert', 'Gâteau Basque à la crème pâtissière', 'Gluten, Lait, Oeufs'),
(241, 'Un Velouté de butternut', 'entree', 'Un Velouté de butternut au lard croustillant', 'Lait'),
(242, 'Magret de canard', 'plat', 'Magret de canard aux pommes de terre sarladaises', 'Aucun'),
(243, 'Fondant au chocolat', 'dessert', 'Fondant au chocolat noir coulant', 'Lait, Oeufs, Gluten'),
(244, 'Soupe oignon gratinée', 'entree', 'Une Soupe à l’oignon gratinée', 'Gluten, Lait'),
(245, 'Boeuf Bourguignon', 'plat', 'Boeuf Bourguignon mijoté au vin rouge', 'Aucun'),
(247, 'Tarte Tatin', 'dessert', 'Tarte Tatin aux pommes caramélisées', 'Gluten, Lait'),
(248, 'Un Velouté de petits pois', 'entree', 'Un Velouté de petits pois à la menthe fraîche', 'Lait'),
(249, 'Tarte rustique asperges', 'plat', 'Tarte rustique asperges et petits pois dorée', 'Gluten'),
(250, 'Fraisier léger', 'dessert', 'Fraisier léger à la crème fouettée', 'Lait, Gluten');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int NOT NULL,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mot_de_passe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gsm` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `adresse` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `role` enum('visiteur','client','employe','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'client',
  `actif` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `gsm`, `adresse`, `role`, `actif`, `created_at`) VALUES
(1, 'Admin', 'Super', 'admin@test.fr', '$2y$10$Y9SufdrWe1QXPwhZI.SJt.IsSbe58c8IYGnxRlp7hb6MUv8xQnFza', '0600000001', '1 Rue Admin, Bordeaux', 'admin', 1, '2026-02-25 02:57:15'),
(11, 'Dupont ', 'Marion', 'marion@test.fr', '$2y$10$x/si4LONofYXQn46ehSHr.MnBFCVFcQZf2L5Bl1zmxJoLUNGEU0yi', NULL, NULL, 'employe', 1, '2026-10-05 08:54:44'),
(12, 'Doe', 'John', 'jacquet-m@protonmail.com', '$2y$10$S5/q2Vimrlqp7rXtaCQ6..M3p8RDGE0vgn77X68UTSMp8qkQ.AZQ.', '+262693852112', '21 rue des goyaves\r\napt 10 residence quartier latin', 'client', 1, '2026-10-05 12:00:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `commande_id` (`commande_id`);

--
-- Indexes for table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `commande_statuts`
--
ALTER TABLE `commande_statuts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commande_id` (`commande_id`);

--
-- Indexes for table `horaires`
--
ALTER TABLE `horaires`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_images`
--
ALTER TABLE `menu_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `menu_plats`
--
ALTER TABLE `menu_plats`
  ADD PRIMARY KEY (`menu_id`,`plat_id`),
  ADD KEY `idx_menu_plats_plat` (`plat_id`);

--
-- Indexes for table `plats`
--
ALTER TABLE `plats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `commande_statuts`
--
ALTER TABLE `commande_statuts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `horaires`
--
ALTER TABLE `horaires`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `menu_images`
--
ALTER TABLE `menu_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `plats`
--
ALTER TABLE `plats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`);

--
-- Constraints for table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`);

--
-- Constraints for table `commande_statuts`
--
ALTER TABLE `commande_statuts`
  ADD CONSTRAINT `commande_statuts_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`);

--
-- Constraints for table `menu_images`
--
ALTER TABLE `menu_images`
  ADD CONSTRAINT `menu_images_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_plats`
--
ALTER TABLE `menu_plats`
  ADD CONSTRAINT `fk_menu_plats_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_menu_plats_plat` FOREIGN KEY (`plat_id`) REFERENCES `plats` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
