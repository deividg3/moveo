<?php
// backend/login.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/db.php';

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

$email    = trim($data['email']    ?? '');
$password = $data['password']      ?? '';

if (!$email || !$password) {
    http_response_code(422);
    echo json_encode(['error' => 'Faltan campos']);
    exit;
}

try {
    $db   = getDB();
    $stmt = $db->prepare('SELECT id, name, password_hash FROM users WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Credenciales incorrectas']);
        exit;
    }

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'user_id' => $user['id'],
        'name'    => $user['name'],
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error interno', 'detalle' => $e->getMessage()]);
}
