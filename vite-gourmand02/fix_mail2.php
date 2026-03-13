<?php
$pass = getenv('MAIL_PASS');
echo $pass ? 'MAIL_PASS OK: ' . substr($pass, 0, 4) . '...' : 'MAIL_PASS VIDE';