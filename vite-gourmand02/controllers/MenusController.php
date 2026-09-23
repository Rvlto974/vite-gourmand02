<?php
require_once __DIR__ . '/../models/MenuModel.php';

class MenusController {
    private $menuModel;

    public function __construct() {
        $this->menuModel = new MenuModel();
    }

    public function index() {
        $filtres = [
            'theme' => $_GET['theme'] ?? '',
            'regime' => $_GET['regime'] ?? '',
            'prix_min' => $_GET['prix_min'] ?? '',
            'prix_max' => $_GET['prix_max'] ?? '',
        ];
        $menus = $this->menuModel->getAll($filtres);
        require __DIR__ . '/../views/menus/index.php';
    }

    public function detail() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /menus');
            exit;
        }
        $menu = $this->menuModel->getById($id);
        if (!$menu) {
            http_response_code(404);
            echo "Menu non trouvé";
            exit;
        }
        $plats = $this->menuModel->getPlatsById($id);
        $avis = $this->menuModel->getAvisById($id);
        $images = $this->menuModel->getImagesById($id);
        require __DIR__ . '/../views/menus/detail.php';
    }
}
?>
