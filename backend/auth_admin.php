<?php
// backend/auth_admin.php
// Incluir en cada endpoint protegido: require_once __DIR__ . '/auth_admin.php';
 
require_once __DIR__ . '/db.php';
 
function requireAdmin(): int {
    $headers = getallheaders();
    $auth    = $headers['Authorization'] ?? $headers['authorization'] ?? '';
 
    if (!preg_match('/^Bearer\s+(.+)$/i', $auth, $m)) {
        http_response_code(401);
        echo json_encode(['error' => 'Token requerido']);
        exit;
    }
 
    $token = $m[1];
 
    try {
        $db   = getDB();
        $stmt = $db->prepare('
            SELECT admin_id FROM admin_sessions
            WHERE token = :t AND expires_at > NOW()
            LIMIT 1
        ');
        $stmt->execute([':t' => $token]);
        $row = $stmt->fetch();
 
        if (!$row) {
            http_response_code(401);
            echo json_encode(['error' => 'Sesión caducada o inválida']);
            exit;
        }
 
        return (int)$row['admin_id'];
 
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error interno']);
        exit;
    }
}