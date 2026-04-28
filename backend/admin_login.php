<?php
// backend/admin_login.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
 
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
 
require_once __DIR__ . '/db.php';
 
$data     = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';
 
if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Credenciales requeridas']);
    exit;
}
 
try {
    $db = getDB();
 
    $stmt = $db->prepare('SELECT id, password_hash FROM admins WHERE username = :u LIMIT 1');
    $stmt->execute([':u' => $username]);
    $admin = $stmt->fetch();
 
    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Usuario o contraseña incorrectos']);
        exit;
    }
 
    // Generar token seguro
    $token   = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+8 hours'));
 
    // Limpiar sesiones antiguas del mismo admin
    $db->prepare('DELETE FROM admin_sessions WHERE admin_id = :id')
       ->execute([':id' => $admin['id']]);
 
    $db->prepare('INSERT INTO admin_sessions (token, admin_id, expires_at) VALUES (:t, :id, :e)')
       ->execute([':t' => $token, ':id' => $admin['id'], ':e' => $expires]);
 
    echo json_encode(['success' => true, 'token' => $token]);
 
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error interno']);
}