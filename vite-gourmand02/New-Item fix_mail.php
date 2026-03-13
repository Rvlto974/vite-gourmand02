<?php
require_once 'config/config.php';
require_once 'config/EmailService.php';

$emailService = new EmailService();
$result = $emailService->envoyerCommandeTerminee(
    'mathieujacquet97460@gmail.com',
    'Test',
    ['menu_titre' => 'Menu Test'],
    999
);

echo $result ? '✅ Mail envoyé !' : '❌ Échec envoi mail';