<?php
require_once 'models/MenuModel.php';

class MenusController {
    private $menuModel;

    public function __construct() {
        $this->menuModel = new MenuModel();
    }

    // Page liste des menus
    public function index() {
        $menus = $this->menuModel->getAll();
        require_once 'views/menus/index.php';
    }

    // Page détail d'un menu
    public function detail() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /menus');
            exit;
        }
        $menu = $this->menuModel->getById($id);
        require_once 'views/menus/detail.php';
    }
}