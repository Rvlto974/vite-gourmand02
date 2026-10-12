<?php

/**
 * Classe Database
 *
 * Cette classe permet de se connecter à une base de données
 * MySQL en utilisant PDO (PHP Data Objects).
 *
 * Elle fonctionne avec :
 * - Docker et MySQL en développement local ;
 * - Fly.io et la base MySQL filess.io en production.
 *
 * Les paramètres de connexion sont récupérés depuis
 * les variables d'environnement du serveur ou du fichier .env.
 */

class Database
{
    /**
     * Paramètres de connexion MySQL.
     *
     * host     : adresse du serveur MySQL
     * dbname   : nom de la base de données
     * username : utilisateur MySQL
     * password : mot de passe MySQL
     * port     : port de connexion MySQL
     */
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $port;

    /**
     * Stocke la connexion PDO.
     *
     * Le mot-clé static permet de partager la même connexion
     * entre les différentes instances de cette classe,
     * pendant une même requête PHP.
     */
    private static $pdo = null;

    /**
     * Constructeur de la classe.
     *
     * Il est exécuté lorsqu'on crée un objet Database :
     *
     * $database = new Database();
     *
     * getenv() récupère les variables d'environnement
     * fournies notamment par Fly.io.
     *
     * $_ENV permet de récupérer les variables chargées
     * depuis le fichier .env local.
     *
     * Les valeurs par défaut correspondent à Docker.
     */
    public function __construct()
    {
        // Adresse du serveur MySQL.
        $this->host = getenv('DB_HOST')
            ?: ($_ENV['DB_HOST'] ?? 'mysql');

        // Nom de la base de données.
        $this->dbname = getenv('DB_NAME')
            ?: ($_ENV['DB_NAME'] ?? 'vite_gourmand');

        // Nom de l'utilisateur MySQL.
        $this->username = getenv('DB_USER')
            ?: ($_ENV['DB_USER'] ?? 'root');

        // Mot de passe de connexion MySQL.
        $this->password = getenv('DB_PASS')
            ?: ($_ENV['DB_PASS'] ?? 'root');

        // Port MySQL :
        // - Docker utilise généralement 3306.
        // - Ton hébergement filess.io utilise 3307.
        $this->port = getenv('DB_PORT')
            ?: ($_ENV['DB_PORT'] ?? '3306');
    }

    /**
     * Méthode connect()
     *
     * Établit une connexion à MySQL et retourne
     * un objet PDO utilisable dans les modèles.
     *
     * Si une connexion existe déjà pendant la requête,
     * elle est réutilisée.
     *
     * @return PDO
     */
    public function connect()
    {
        // Vérifie si aucune connexion n'a encore été créée.
        if (self::$pdo === null) {

            try {
                /**
                 * Construction du DSN.
                 *
                 * DSN signifie Data Source Name.
                 * Il contient les informations nécessaires
                 * pour identifier le serveur MySQL.
                 *
                 * utf8mb4 permet de gérer correctement
                 * les accents, les caractères spéciaux
                 * et les emojis.
                 */
                $dsn = "mysql:host={$this->host};"
                     . "port={$this->port};"
                     . "dbname={$this->dbname};"
                     . "charset=utf8mb4";

                /**
                 * Création de la connexion PDO.
                 */
                self::$pdo = new PDO(
                    $dsn,
                    $this->username,
                    $this->password,
                    [
                        /**
                         * PDO::ATTR_ERRMODE
                         *
                         * Déclenche une exception lorsqu'une
                         * erreur SQL ou de connexion survient.
                         */
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

                        /**
                         * PDO::ATTR_PERSISTENT
                         *
                         * Désactive les connexions persistantes
                         * pour éviter de conserver inutilement
                         * des connexions ouvertes.
                         */
                        PDO::ATTR_PERSISTENT => false,

                        /**
                         * PDO::ATTR_DEFAULT_FETCH_MODE
                         *
                         * Les résultats des requêtes sont
                         * retournés sous forme de tableaux
                         * associatifs.
                         *
                         * Exemple :
                         * $utilisateur['email']
                         */
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );

            } catch (PDOException $e) {

                /**
                 * Gestion des erreurs.
                 *
                 * L'erreur détaillée est enregistrée dans
                 * les journaux du serveur.
                 *
                 * On évite de l'afficher aux visiteurs
                 * pour ne pas révéler les paramètres MySQL.
                 */
                error_log(
                    "Erreur de connexion MySQL : " . $e->getMessage()
                );

                // Indique au navigateur une erreur serveur.
                http_response_code(500);

                // Affiche un message générique.
                exit(
                    "Erreur de connexion à la base de données."
                );
            }
        }

        /**
         * Retourne la connexion PDO existante
         * ou celle qui vient d'être créée.
         */
        return self::$pdo;
    }
}
