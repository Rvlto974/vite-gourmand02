<?php
require_once 'models/MenuModel.php';

class MenusController {
    private $menuModel;

    public function __construct() {
        $this->menuModel = new MenuModel();
    }

    public function index() {
        $filtres = [
            'theme'        => $_GET['theme'] ?? '',
            'regime'       => $_GET['regime'] ?? '',
            'prix_min'     => $_GET['prix_min'] ?? '',
            'prix_max'     => $_GET['prix_max'] ?? '',
            'nb_personnes' => $_GET['nb_personnes'] ?? '',
            'tri'          => $_GET['tri'] ?? 'recent'
        ];

        $menus = $this->menuModel->getAll($filtres);
        require_once 'views/menus/index.php';
    }

    public function detail() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /menus');
            exit;
        }
        $menu = $this->menuModel->getById($id);
        if (!$menu) {
            header('Location: /menus');
            exit;
        }
        require_once 'views/menus/detail.php';
    }

    public function filtrer() {
        $filtres = [
            'theme'        => $_GET['theme'] ?? '',
            'regime'       => $_GET['regime'] ?? '',
            'prix_min'     => $_GET['prix_min'] ?? '',
            'prix_max'     => $_GET['prix_max'] ?? '',
            'nb_personnes' => $_GET['nb_personnes'] ?? '',
            'tri'          => $_GET['tri'] ?? 'recent'
        ];

        $menus = $this->menuModel->getAll($filtres);
        header('Content-Type: application/json');
        echo json_encode($menus);
        exit;
    }
}