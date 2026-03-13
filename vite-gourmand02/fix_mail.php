<?php
require_once 'vendor/autoload.php';
require_once 'config/EmailService.php';

$emailService = new EmailService();
$result = $emailService->envoyerCommandeTerminee(
    'jacquet-m@protonmail.com',
    'Test',
    ['menu_titre' => 'Menu Test'],
    999
);

echo $result ? '✅ Mail envoyé !' : '❌ Échec envoi mail';