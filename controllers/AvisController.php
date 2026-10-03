<?php
require_once 'models/AvisModel.php';
require_once 'models/CommandeModel.php';

class AvisController {
    private $avisModel;
    private $commandeModel;

    public function __construct() {
        $this->avisModel = new AvisModel();
        $this->commandeModel = new CommandeModel();
    }

    private function verifierConnexion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/connexion');
            exit;
        }
    }

    public function creer() {
        $this->verifierConnexion();

        $commande_id = $_GET['commande_id'] ?? null;
        if (!$commande_id) {
            header('Location: /commandes/historique');
            exit;
        }

        $commande = $this->commandeModel->getById($commande_id);

        if (!$commande || $commande['utilisateur_id'] != $_SESSION['user_id']) {
            header('Location: /commandes/historique');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $note = $_POST['note'] ?? 5;
            $commentaire = $_POST['commentaire'] ?? '';

            $this->avisModel->creer([
                'utilisateur_id' => $_SESSION['user_id'],
                'commande_id' => $commande_id,
                'note' => $note,
                'commentaire' => $commentaire
            ]);

            header('Location: /commandes/historique?avis=ok');
            exit;
        }

        require_once 'views/avis/creer.php';
    }
}