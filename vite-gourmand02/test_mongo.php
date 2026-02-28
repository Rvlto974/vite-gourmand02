<?php
require_once 'vendor/autoload.php';
$client = new MongoDB\Client('mongodb://mongodb:27017');
echo 'MongoDB OK';