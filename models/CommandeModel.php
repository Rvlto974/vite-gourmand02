<?php

// Charge la configuration de connexion à la base MySQL.
require_once 'config/database.php';

/**
 * Modèle chargé de la gestion des commandes.
 *
 * MySQL contient les données officielles des commandes.
 * MongoDB contient une copie des informations nécessaires aux statistiques.
 */
class CommandeModel
{
    private $db;

    /** Initialise la connexion à MySQL. */
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * Synchronise une commande avec MongoDB à partir des données MySQL.
     *
     * La méthode est privée : toutes les opérations de modification
     * l'appellent au même endroit pour éviter la duplication de code.
     * Une erreur MongoDB est journalisée mais ne remet pas en cause
     * une opération MySQL déjà réussie.
     *
     * @param int $id Identifiant de la commande dans MySQL.
     * @return bool true si la synchronisation a réussi, false sinon.
     */
    private function synchroniserMongoDB($id)
    {
        try {
            // Récupère la version officielle et actuelle de la commande.
            $commande = $this->getById($id);

            if (!$commande) {
                error_log('[CommandeModel] Commande introuvable pour la synchronisation : ' . $id);
                return false;
            }

            // Charge le service MongoDB uniquement lorsque nécessaire.
            require_once __DIR__ . '/../config/MongoService.php';
            $mongo = new MongoService();

            if (!$mongo->isConnected()) {
                error_log('[CommandeModel] MongoDB indisponible pour la commande #' . $id);
                return false;
            }

            // L'identifiant MySQL sert de clé pour mettre à jour le même document.
            // Le statut permet d'exclure les annulations des statistiques.
            $resultat = $mongo->enregistrerCommande([
                'commande_id' => $commande['id'],
                'menu_id' => $commande['menu_id'],
                'menu_titre' => $commande['menu_titre'],
                'prix_total' => $commande['prix_total'],
                'nb_personnes' => $commande['nb_personnes'],
                'date' => $commande['date_prestation'],
                'statut' => $commande['statut']
            ]);

            if (!$resultat) {
                error_log('[CommandeModel] Échec de synchronisation MongoDB pour la commande #' . $id);
            }

            return (bool) $resultat;
        } catch (\Throwable $e) {
            // Ne bloque pas le parcours client ou employé si MongoDB échoue.
            error_log('[CommandeModel] Synchronisation MongoDB : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Crée une commande dans MySQL puis la synchronise avec MongoDB.
     *
     * @param array $data Données fournies par le contrôleur.
     * @return int Identifiant de la nouvelle commande.
     */
    public function creer($data)
    {
        // Le statut initial d'une commande est toujours « nouvelle ».
        $sql = "INSERT INTO commandes
                (utilisateur_id, menu_id, nb_personnes, prix_total,
                 statut, adresse_livraison, date_prestation)
                VALUES
                (:utilisateur_id, :menu_id, :nb_personnes, :prix_total,
                 'nouvelle', :adresse_livraison, :date_prestation)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':utilisateur_id' => $data['utilisateur_id'],
            ':menu_id' => $data['menu_id'],
            ':nb_personnes' => $data['nb_personnes'],
            ':prix_total' => $data['prix_total'],
            ':adresse_livraison' => $data['adresse_livraison'],
            ':date_prestation' => $data['date_prestation']
        ]);

        // Conserve le même type de retour que la méthode précédente.
        $commandeId = (int) $this->db->lastInsertId();

        // La commande MySQL existe déjà : MongoDB est une étape secondaire.
        $this->synchroniserMongoDB($commandeId);

        return $commandeId;
    }

    /** Retourne les commandes d'un client, de la plus récente à la plus ancienne. */
    public function getByUtilisateur($utilisateur_id)
    {
        $sql = "SELECT c.*, m.titre AS menu_titre
                FROM commandes c
                JOIN menus m ON c.menu_id = m.id
                WHERE c.utilisateur_id = :utilisateur_id
                ORDER BY c.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateur_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Récupère une commande et le titre de son menu depuis MySQL. */
    public function getById($id)
    {
        $sql = "SELECT c.*, m.titre AS menu_titre
                FROM commandes c
                JOIN menus m ON c.menu_id = m.id
                WHERE c.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Annule une commande depuis l'espace client.
     * Le document MongoDB est conservé avec le statut « annulee ».
     */
    public function annuler($id)
    {
        $sql = "UPDATE commandes SET statut = 'annulee' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $resultat = $stmt->execute([':id' => $id]);

        if ($resultat) {
            $this->synchroniserMongoDB($id);
        }

        return $resultat;
    }

    /** Retourne les commandes et les informations clients pour l'espace employé. */
    public function getAllCommandes()
    {
        $sql = "SELECT c.*, m.titre AS menu_titre, u.nom, u.prenom
                FROM commandes c
                JOIN menus m ON c.menu_id = m.id
                JOIN utilisateurs u ON c.utilisateur_id = u.id
                ORDER BY c.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour le statut d'une commande depuis l'espace employé.
     * Le nouveau statut est ensuite répercuté dans MongoDB.
     */
    public function updateStatut($id, $statut)
    {
        $sql = "UPDATE commandes SET statut = :statut WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $resultat = $stmt->execute([
            ':statut' => $statut,
            ':id' => $id
        ]);

        if ($resultat) {
            $this->synchroniserMongoDB($id);
        }

        return $resultat;
    }

    /**
     * Modifie les informations d'une commande dans MySQL.
     * Le montant, le nombre de personnes et la date de prestation
     * sont ensuite actualisés dans MongoDB.
     */
    public function modifier($id, $data)
    {
        $sql = "UPDATE commandes SET
                    nb_personnes = :nb_personnes,
                    adresse_livraison = :adresse_livraison,
                    date_prestation = :date_prestation,
                    prix_total = :prix_total
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $resultat = $stmt->execute([
            ':nb_personnes' => $data['nb_personnes'],
            ':adresse_livraison' => $data['adresse_livraison'],
            ':date_prestation' => $data['date_prestation'],
            ':prix_total' => $data['prix_total'],
            ':id' => $id
        ]);

        if ($resultat) {
            $this->synchroniserMongoDB($id);
        }

        return $resultat;
    }

    /**
     * Annule une commande depuis l'espace employé.
     * Le motif et le mode de contact sont conservés dans MySQL.
     * Le statut « annulee » est répercuté dans MongoDB.
     */
    public function annulerParEmploye($id, $motif, $mode_contact)
    {
        $sql = "UPDATE commandes
                SET statut = 'annulee',
                    motif_annulation = :motif,
                    mode_contact = :mode_contact
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $resultat = $stmt->execute([
            ':motif' => $motif,
            ':mode_contact' => $mode_contact,
            ':id' => $id
        ]);

        if ($resultat) {
            $this->synchroniserMongoDB($id);
        }

        return $resultat;
    }
}
