<?php
require_once __DIR__ . "/../../config/database_sqlite.php";

class ApiController {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    // === AVIS: CREATE ===
    public function createAvis($data) {
        $utilisateur_id = $_SESSION['utilisateur_id'] ?? 1;
        
        if (!isset($data['commande_id']) || !isset($data['note'])) {
            return ['success' => false, 'error' => 'commande_id et note requis'];
        }

        try {
            $sql = 'INSERT INTO avis (utilisateur_id, commande_id, note, commentaire, valide, created_at) 
                    VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $utilisateur_id,
                (int)$data['commande_id'],
                (int)$data['note'],
                $data['commentaire'] ?? null,
                0
            ]);

            return ['success' => true, 'data' => ['id' => $this->db->lastInsertId()]];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // === AVIS: READ ALL (validés seulement) ===
    public function getAvis() {
        try {
            $sql = 'SELECT a.*, u.prenom, u.nom FROM avis a 
                    LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id 
                    WHERE a.valide = 1 ORDER BY a.created_at DESC LIMIT 10';
            $result = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // === AVIS: READ BY ID (tous, validés ou non) ===
    public function getAvisById($id) {
        try {
            $sql = 'SELECT a.*, u.prenom, u.nom FROM avis a 
                    LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id 
                    WHERE a.id = ?';  // ← Pas de WHERE valide=1 ici
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                return ['success' => false, 'error' => 'Avis non trouvé'];
            }
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // === AVIS: UPDATE ===
    public function updateAvis($id, $data) {
        try {
            // Vérifie que l'avis existe
            $check = $this->db->prepare('SELECT id FROM avis WHERE id = ?');
            $check->execute([$id]);
            if (!$check->fetch()) {
                return ['success' => false, 'error' => 'Avis non trouvé'];
            }

            $updates = [];
            $values = [];
            
            if (isset($data['note'])) {
                $updates[] = 'note = ?';
                $values[] = (int)$data['note'];
            }
            if (isset($data['commentaire'])) {
                $updates[] = 'commentaire = ?';
                $values[] = $data['commentaire'];
            }
            if (isset($data['valide'])) {
                $updates[] = 'valide = ?';
                $values[] = (int)$data['valide'];
            }

            if (empty($updates)) {
                return ['success' => false, 'error' => 'Aucun champ à mettre à jour'];
            }

            $values[] = $id;
            $sql = 'UPDATE avis SET ' . implode(', ', $updates) . ' WHERE id = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);

            return ['success' => true, 'data' => ['message' => 'Avis mis à jour']];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // === AVIS: DELETE ===
    public function deleteAvis($id) {
        try {
            // Vérifie que l'avis existe
            $check = $this->db->prepare('SELECT id FROM avis WHERE id = ?');
            $check->execute([$id]);
            if (!$check->fetch()) {
                return ['success' => false, 'error' => 'Avis non trouvé'];
            }

            $sql = 'DELETE FROM avis WHERE id = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);

            return ['success' => true, 'data' => ['message' => 'Avis supprimé']];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // ==================== MENUS ====================
    public function getMenus() {
        try {
            $sql = 'SELECT * FROM menus ORDER BY id DESC';
            $result = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getMenuById($id) {
        try {
            $sql = 'SELECT * FROM menus WHERE id = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                return ['success' => false, 'error' => 'Menu non trouvé'];
            }
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // ==================== PLATS ====================
    public function getPlats() {
        try {
            $sql = 'SELECT * FROM plats ORDER BY id DESC';
            $result = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getPlatById($id) {
        try {
            $sql = 'SELECT * FROM plats WHERE id = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                return ['success' => false, 'error' => 'Plat non trouvé'];
            }
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // ==================== COMMANDES ====================
    public function getCommandes() {
        try {
            $sql = 'SELECT * FROM commandes ORDER BY id DESC';
            $result = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getCommandeById($id) {
        try {
            $sql = 'SELECT * FROM commandes WHERE id = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                return ['success' => false, 'error' => 'Commande non trouvée'];
            }
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function createCommande($data) {
        try {
            $sql = 'INSERT INTO commandes (utilisateur_id, menu_id, nb_personnes, prix_total, adresse_livraison, date_prestation, statut) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['utilisateur_id'] ?? 1,
                (int)$data['menu_id'],
                (int)$data['nb_personnes'],
                (int)$data['prix_total'] ?? 0,
                $data['adresse_livraison'],
                $data['date_prestation'],
                'en_attente'
            ]);

            return ['success' => true, 'data' => ['id' => $this->db->lastInsertId()]];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function updateCommande($id, $data) {
        try {
            $check = $this->db->prepare('SELECT id FROM commandes WHERE id = ?');
            $check->execute([$id]);
            if (!$check->fetch()) {
                return ['success' => false, 'error' => 'Commande non trouvée'];
            }

            $updates = [];
            $values = [];
            
            if (isset($data['statut'])) {
                $updates[] = 'statut = ?';
                $values[] = $data['statut'];
            }
            if (isset($data['prix_total'])) {
                $updates[] = 'prix_total = ?';
                $values[] = (int)$data['prix_total'];
            }

            if (empty($updates)) {
                return ['success' => false, 'error' => 'Aucun champ à mettre à jour'];
            }

            $values[] = $id;
            $sql = 'UPDATE commandes SET ' . implode(', ', $updates) . ' WHERE id = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);

            return ['success' => true, 'data' => ['id' => $id]];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function deleteCommande($id) {
        try {
            $check = $this->db->prepare('SELECT id FROM commandes WHERE id = ?');
            $check->execute([$id]);
            if (!$check->fetch()) {
                return ['success' => false, 'error' => 'Commande non trouvée'];
            }

            $sql = 'DELETE FROM commandes WHERE id = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);

            return ['success' => true, 'data' => ['message' => 'Commande supprimée']];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getCommandeStatuts($id) {
        try {
            $sql = 'SELECT * FROM commande_statuts WHERE commande_id = ? ORDER BY created_at DESC';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // ==================== UTILISATEURS ====================
    public function getUtilisateurs() {
        try {
            $sql = 'SELECT * FROM utilisateurs ORDER BY id DESC';
            $result = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getUtilisateurById($id) {
        try {
            $sql = 'SELECT * FROM utilisateurs WHERE id = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                return ['success' => false, 'error' => 'Utilisateur non trouvé'];
            }
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function createUtilisateur($data) {
        try {
            $sql = 'INSERT INTO utilisateurs (prenom, nom, email) VALUES (?, ?, ?)';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['prenom'],
                $data['nom'],
                $data['email']
            ]);

            return ['success' => true, 'data' => ['id' => $this->db->lastInsertId()]];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function updateUtilisateur($id, $data) {
        try {
            $check = $this->db->prepare('SELECT id FROM utilisateurs WHERE id = ?');
            $check->execute([$id]);
            if (!$check->fetch()) {
                return ['success' => false, 'error' => 'Utilisateur non trouvé'];
            }

            $updates = [];
            $values = [];
            
            foreach (['prenom', 'nom', 'email'] as $field) {
                if (isset($data[$field])) {
                    $updates[] = "$field = ?";
                    $values[] = $data[$field];
                }
            }

            if (empty($updates)) {
                return ['success' => false, 'error' => 'Aucun champ à mettre à jour'];
            }

            $values[] = $id;
            $sql = 'UPDATE utilisateurs SET ' . implode(', ', $updates) . ' WHERE id = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);

            return ['success' => true, 'data' => ['message' => 'Utilisateur mis à jour']];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function deleteUtilisateur($id) {
        try {
            $check = $this->db->prepare('SELECT id FROM utilisateurs WHERE id = ?');
            $check->execute([$id]);
            if (!$check->fetch()) {
                return ['success' => false, 'error' => 'Utilisateur non trouvé'];
            }

            $sql = 'DELETE FROM utilisateurs WHERE id = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);

            return ['success' => true, 'data' => ['message' => 'Utilisateur supprimé']];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
