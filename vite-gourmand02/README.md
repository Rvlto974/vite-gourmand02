# vite-gourmand02
Application Web Restauration

# 🍽️ Vite & Gourmand

Application web de commande en ligne pour le traiteur Vite & Gourmand à Bordeaux.

## 📋 Description

Projet réalisé dans le cadre du titre professionnel **Développeur Web et Web Mobile (DWWM)**.

## 🚀 Technologies utilisées

- **Frontend** : HTML5, CSS3, Bootstrap 5, JavaScript
- **Backend** : PHP 8 avec PDO
- **BDD Relationnelle** : MySQL 8
- **BDD NoSQL** : MongoDB
- **Conteneurisation** : Docker
- **Déploiement** : fly.io

## ⚙️ Installation locale

### Prérequis
- Docker Desktop
- Git

### Étapes

1. Cloner le dépôt
git clone https://github.com/Rvlto974/vite-gourmand02.git

2. Copier le fichier d'environnement
cp .env.example .env

3. Lancer Docker
docker-compose up -d

4. Importer la base de données
Importer le fichier `database/vite_gourmand.sql`

5. Accéder à l'application
http://localhost:8080

## 👥 Comptes de test

| Rôle | Email | Mot de passe |
|---|---|---|
| Client | client@test.fr | Test@12345 |
| Employé | employe@test.fr | Test@12345 |
| Administrateur | admin@test.fr | Test@12345 |

## 📁 Structure du projet
```
├── assets/
├── controllers/
├── models/
├── views/
├── database/
├── docker-compose.yml
└── README.md
```

## 🔗 Liens

- [GitHub Projects](https://github.com/users/Rvlto974/projects/1)