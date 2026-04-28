<?php
// backend/get_stats.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
 
require_once __DIR__ . '/auth_admin.php';
requireAdmin();
require_once __DIR__ . '/db.php';
 
try {
    $db = getDB();
 
    $stats = [];
 
    // Total usuarios
    $stats['total_users'] = (int)$db->query('SELECT COUNT(*) FROM users')->fetchColumn();
 
    // Registros hoy
    $stats['new_today'] = (int)$db->query(
        "SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE()"
    )->fetchColumn();
 
    // Total preguntas (banco fijo en JSON, pero podemos devolver una constante)
    $stats['total_questions'] = 30; // Actualiza si añades más
 
    // Racha media
    $avg = $db->query('SELECT AVG(streak) FROM users')->fetchColumn();
    $stats['avg_streak'] = $avg !== null ? round((float)$avg, 1) : 0;
 
    // Últimos 20 registros
    $recentUsers = $db->query(
        'SELECT id, name, email, goals, birthdate, streak, created_at
         FROM users ORDER BY created_at DESC LIMIT 20'
    )->fetchAll();
 
    echo json_encode(['stats' => $stats, 'recent_users' => $recentUsers]);
 
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error interno']);
}