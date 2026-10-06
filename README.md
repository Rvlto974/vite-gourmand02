
# Vite & Gourmand

Application web de traiteur réalisée dans le cadre de l’ECF du titre professionnel **Développeur Web et Web Mobile**.

Vite & Gourmand est une entreprise de traiteur basée à Bordeaux. L’application permet de consulter les menus, de créer un compte et de gérer des commandes. Des espaces dédiés sont prévus pour les clients, les employés et l’administrateur.

## Liens

- **Application en ligne :** https://rvlto974.github.io/vite-gourmand02/
- **Dépôt GitHub :** https://github.com/Rvlto974/vite-gourmand02

> L’application publiée sur GitHub Pages peut présenter uniquement la partie statique du site. Les fonctionnalités nécessitant PHP ou une base de données doivent être testées sur l’environnement prévu pour le back-end.

## Fonctionnalités

### Visiteurs

- Consulter les menus et leurs détails
- Rechercher ou filtrer les menus
- Consulter les avis publiés
- Créer un compte
- Contacter l’entreprise

### Clients connectés

- Passer une commande
- Consulter le détail et le suivi de leurs commandes
- Modifier ou annuler une commande selon son statut
- Modifier leurs informations personnelles
- Déposer un avis après une commande terminée

### Employés

- Gérer les menus, les plats et les horaires
- Consulter les commandes et mettre à jour leur statut
- Traiter les avis clients

### Administrateur

- Accéder aux fonctionnalités de l’espace employé
- Gérer les comptes employés
- Consulter les statistiques de commandes et de chiffre d’affaires

> Certaines fonctionnalités peuvent être en cours de développement. Seules les fonctionnalités effectivement opérationnelles doivent être présentées comme disponibles lors de la démonstration.

## Technologies

- **Front-end :** HTML, CSS, JavaScript, Bootstrap
- **Back-end :** PHP
- **Accès aux données :** PDO
- **Base de données relationnelle :** MySQL ou MariaDB
- **Base de données non relationnelle :** MongoDB, pour les statistiques si cette fonctionnalité est configurée
- **Serveur local :** Apache
- **Conteneurisation :** Docker, si utilisée avec la configuration fournie par le projet

## Prérequis

Installer les outils nécessaires à l’environnement du projet :

- PHP : [indiquer la version utilisée]
- MySQL ou MariaDB : [indiquer la version utilisée]
- Apache
- Git
- Composer : [si le projet utilise des dépendances Composer]
- MongoDB : [si nécessaire pour les statistiques]
- Docker : [si le lancement s’effectue avec Docker]

## Installation en local

### 1. Cloner le dépôt

```bash
git clone https://github.com/Rvlto974/vite-gourmand02.git
cd vite-gourmand02
```

Pour récupérer la branche de correction, si nécessaire :

```bash
git checkout correction/retours-correcteur
```

### 2. Configurer l’environnement

Créer un fichier `.env` à partir du fichier d’exemple :

```bash
cp .env.example .env
```

Sous PowerShell :

```powershell
Copy-Item .env.example .env
```

Renseigner dans `.env` les paramètres correspondant à l’environnement local : connexion à la base de données, configuration de messagerie et autres paramètres requis par l’application.

**Ne jamais publier le fichier `.env`, des mots de passe ou des clés secrètes sur GitHub.**

### 3. Créer et initialiser la base de données

Créer une base de données locale, puis importer le script SQL présent dans le dossier `database/`.

Fichier SQL à utiliser : **[indiquer le nom exact du fichier après vérification du dossier `database/`]**.

Exemple depuis un terminal MySQL :

```bash
mysql -u [utilisateur] -p [nom_de_la_base] < database/[fichier_sql].sql
```

Si les données de démonstration se trouvent dans un fichier distinct, l’importer également après la création du schéma.

### 4. Installer les dépendances

Si le projet contient un fichier `composer.json`, installer les dépendances avec :

```bash
composer install
```

Si le projet n’utilise pas Composer, ignorer cette étape.

### 5. Lancer l’application

Configurer Apache afin que la racine du site pointe vers le dossier de l’application.

L’adresse locale dépend de la configuration utilisée, par exemple :

```text
http://localhost/vite-gourmand02/
```

Si le projet fournit une configuration Docker fonctionnelle, utiliser les commandes documentées dans les fichiers Docker du dépôt. Ne pas lancer `docker compose up` si aucun fichier `compose.yaml` ou `docker-compose.yml` n’est présent.

## Configuration de la base de données

Les paramètres de connexion doivent être configurés localement et ne doivent pas être ajoutés au dépôt.

Le fichier `.env.example` sert de modèle : il ne doit contenir que des valeurs fictives ou des noms de variables, jamais de véritables identifiants.

La base de données relationnelle contient les données nécessaires au fonctionnement de l’application, notamment les utilisateurs, les menus, les plats et les commandes.

La base de données non relationnelle est destinée aux statistiques de commandes, conformément au besoin du projet, si cette fonctionnalité est effectivement configurée.

## Comptes de démonstration

Ajouter ici les comptes de test fournis pour la démonstration, sans utiliser de comptes ou de mots de passe personnels.

| Rôle          | Identifiant     | Mot de passe    |
| -------------- | --------------- | --------------- |
| Client         | [à compléter] | [à compléter] |
| Employé       | [à compléter] | [à compléter] |
| Administrateur | [à compléter] | [à compléter] |

> Ces comptes doivent être réservés à la démonstration et ne doivent pas être utilisés en production.

## Structure du projet

Principaux éléments du dépôt :

- `assets/` : feuilles de style, scripts JavaScript, images et autres ressources
- `config/` : configuration de l’application et connexion à la base de données
- `controllers/` : traitement des requêtes et logique de contrôle
- `database/` : scripts SQL de création ou d’initialisation de la base
- `models/` : accès aux données et logique associée aux entités
- `views/` : pages et interfaces de l’application
- `Dockerfile` : configuration Docker, si utilisée
- `.env.example` : modèle de configuration locale sans secret
- `Manuel_Utilisateur_Vite_Gourmand.pdf` : guide d’utilisation de l’application

> La structure ci-dessus est à ajuster si certains dossiers ne sont pas présents dans le dépôt.

## Sécurité

Les mesures suivantes doivent être vérifiées dans le code avant d’être considérées comme mises en place :

- Les requêtes SQL utilisent des requêtes préparées avec PDO.
- Les mots de passe sont hachés avant leur stockage.
- L’accès aux espaces client, employé et administrateur est contrôlé selon le rôle.
- Les données envoyées par les formulaires sont validées côté serveur.
- Les informations sensibles sont stockées dans un fichier `.env` non versionné.
- Les messages d’erreur ne révèlent pas de détails sensibles en production.

Si un mot de passe ou un secret a déjà été publié dans Git, le remplacer immédiatement. Le supprimer dans un nouveau commit ne le retire pas de l’historique du dépôt.

## Accessibilité

Le projet vise à proposer une interface accessible et à prendre en compte les recommandations du RGAA : navigation au clavier, contrastes lisibles, libellés de formulaire et textes alternatifs pour les images.

Les contrôles d’accessibilité doivent être réalisés sur l’application elle-même avant de présenter ces exigences comme entièrement satisfaites.

## Documents du projet

- Manuel utilisateur : [`Manuel_Utilisateur_Vite_Gourmand.pdf`](Manuel_Utilisateur_Vite_Gourmand.pdf)
- Modèle conceptuel de données : [indiquer le fichier MCD retenu]
- Documentation technique : [indiquer le chemin ou le lien]
- Documentation de gestion de projet : [indiquer le chemin ou le lien]
- Maquettes et charte graphique : [indiquer le chemin ou le lien]

## Auteur

Projet réalisé par **[Prénom NOM]** dans le cadre de l’ECF du titre professionnel **Développeur Web et Web Mobile**.
