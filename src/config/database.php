<?php
// Configuration des paramètres de connexion
const DB_HOST = 'postgresql-monadev.alwaysdata.net';
const DB_PORT = '5432'; // Port par défaut de PostgreSQL
const DB_NAME = 'monadev_bdmona';
const DB_USER = 'monadev';
const DB_PASSWORD = 'papayanipapa';

try {
    // Création d'une instance de PDO
    $db = new PDO(
        'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';',
        DB_USER,
        DB_PASSWORD
    );

    // Configuration des options PDO
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $err) { die(json_encode(['error' => "Database connection failed {$err->getMessage()}"])); }