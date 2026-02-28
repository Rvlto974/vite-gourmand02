<?php
class PagesController {
    public function mentions() {
        require_once 'views/pages/mentions.php';
    }

    public function cgv() {
        require_once 'views/pages/cgv.php';
    }

    public function confidentialite() {
        require_once 'views/pages/confidentialite.php';
    }
}