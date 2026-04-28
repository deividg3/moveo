<?php
// backend/export_csv.php
// Exporta la lista de usuarios en formato CSV

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth_admin.php';

// Solo admins pueden exportar
requireAdmin();

require_once __DIR__ . '/db.php';

try {
    $db = getDB();

    $type = $_GET['type'] ?? 'users';

    if ($type === 'results') {
        // Exportar resultados de tests
        $stmt = $db->query('
            SELECT tr.id, u.name, u.email, tr.topic, tr.score, tr.total, tr.taken_at
            FROM test_results tr
            JOIN users u ON u.id = tr.user_id
            ORDER BY tr.taken_at DESC
        ');
        $filename = 'moveo_resultados_' . date('Y-m-d') . '.csv';
        $headers = ['ID', 'Nombre', 'Email', 'Tema', 'Aciertos', 'Total', 'Fecha'];
    } else {
        // Exportar usuarios
        $stmt = $db->query('
            SELECT id, name, email, birthdate, goals, streak, last_test_date, created_at
            FROM users
            ORDER BY created_at DESC
        ');
        $filename = 'moveo_usuarios_' . date('Y-m-d') . '.csv';
        $headers = ['ID', 'Nombre', 'Email', 'Fecha Nacimiento', 'Objetivos', 'Racha', 'Último Test', 'Registro'];
    }

    $rows = $stmt->fetchAll();

    // Enviar cabeceras HTTP para descarga CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Access-Control-Allow-Origin: *');

    // BOM para que Excel reconozca UTF-8
    echo "\xEF\xBB\xBF";

    $output = fopen('php://output', 'w');

    // Escribir cabecera
    fputcsv($output, $headers, ';');

    // Escribir filas
    foreach ($rows as $row) {
        fputcsv($output, array_values($row), ';');
    }

    fclose($output);

} catch (PDOException $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => 'Error al exportar CSV']);
}
