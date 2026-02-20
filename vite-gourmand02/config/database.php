<?php 

class Database {
    private $host = $_ENV['DB_HOST'];
    private $dbname = $_ENV['DB_NAME'];
    private $username = $_ENV['DB_USER'];
    private $password = $_ENV['DB_PASS'];
}

# Base de données MySQL
DB_HOST=localhost
DB_NAME=vite_gourmand
DB_USER=root
DB_PASS=

# MongoDB
MONGO_URI=mongodb://localhost:27017
MONGO_DB=vite_gourmand_stats

# Email
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USER=
MAIL_PASS=

# Application
APP_URL=http://localhost:8080
APP_ENV=development