<?php
// backend/db.php

define('DB_HOST', '127.0.0.1'); 
define('DB_PORT', '10005');     // <--- EL PUERTO QUE HAS VISTO
define('DB_NAME', 'local');     // <--- ASEGÚRATE DE QUE SEA 'local'
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8mb4');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            // Construimos el DSN con el puerto por separado, como manda la ley
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'error' => 'Error de conexión',
                'detalle' => $e->getMessage(),
                'ayuda' => 'Revisa que LocalWP esté encendido y el puerto sea el 10005'
            ]);
            exit;
        }
    }
    return $pdo;
}