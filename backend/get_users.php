<?php
// backend/get_users.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
 
require_once __DIR__ . '/auth_admin.php';
requireAdmin();
require_once __DIR__ . '/db.php';
 
try {
    $db = getDB();
 
    $users = $db->query(
        'SELECT id, name, email, goals, birthdate, avatar, streak, last_test_date, created_at
         FROM users
         ORDER BY created_at DESC'
    )->fetchAll();
 
    echo json_encode(['users' => $users]);
 
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error interno']);
}