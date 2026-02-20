USE vite_gourmand;

-- Données de test : Utilisateurs
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, gsm, adresse, role) VALUES
('Admin', 'Super', 'admin@test.fr', '$2y$10$hashadmin123', '0600000001', '1 Rue Admin, Bordeaux', 'admin'),
('Dupont', 'Jean', 'employe@test.fr', '$2y$10$hashemploye123', '0600000002', '2 Rue Employé, Bordeaux', 'employe'),
('Martin', 'Marie', 'client@test.fr', '$2y$10$hashclient123', '0600000003', '3 Rue Client, Bordeaux', 'client');

-- Données de test : Menus
INSERT INTO menus (titre, description, theme, regime, nb_personnes_min, prix_base, stock) VALUES
('Menu Noël Premium', 'Un menu festif et raffiné pour célébrer Noël en famille.', 'Noel', 'classique', 8, 320.00, 10),
('Menu Pâques Tradition', 'Célébrez Pâques avec un menu traditionnel aux saveurs printanières.', 'Paques', 'classique', 6, 280.00, 8),
('Menu Végétal Printemps', 'Un menu 100% végétarien aux saveurs du printemps.', 'classique', 'vegetarien', 4, 200.00, 5);

-- Données de test : Plats
INSERT INTO plats (menu_id, nom, type, description, allergenes) VALUES
(1, 'Foie gras maison', 'entree', 'Foie gras de canard mi-cuit, chutney de figues', 'Gluten'),
(1, 'Dinde aux marrons', 'plat', 'Dinde fermière rôtie, farce aux marrons', 'Oeufs, Fruits à coque'),
(1, 'Bûche au chocolat', 'dessert', 'Bûche traditionnelle au chocolat noir 70%', 'Lait, Oeufs'),
(2, 'Velouté d agneau', 'entree', 'Velouté printanier à l agneau', 'Lait'),
(2, 'Gigot d agneau', 'plat', 'Gigot d agneau rôti aux herbes', 'Aucun'),
(2, 'Tarte aux fraises', 'dessert', 'Tarte aux fraises fraîches', 'Gluten, Lait');

-- Données de test : Commandes
INSERT INTO commandes (utilisateur_id, menu_id, nb_personnes, prix_total, statut, adresse_livraison, date_prestation) VALUES
(3, 1, 8, 320.00, 'terminee', '3 Rue Client, Bordeaux', '2024-12-25 12:00:00'),
(3, 2, 6, 280.00, 'nouvelle', '3 Rue Client, Bordeaux', '2025-04-20 12:00:00');

-- Données de test : Avis
INSERT INTO avis (utilisateur_id, commande_id, note, commentaire, valide) VALUES
(3, 1, 5, 'Excellent menu pour notre repas de Noël en famille !', TRUE);