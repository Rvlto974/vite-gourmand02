<?php
require_once 'models/AvisModel.php';

class AccueilController {
    private $avisModel;

    public function __construct() {
        $this->avisModel = new AvisModel();
    }

    public function index() {
        // Récupère les avis validés pour la page d'accueil
        $avis = $this->avisModel->getAvisValides();
        require_once 'views/accueil/index.php';
    }
}