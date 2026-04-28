<?php
// backend/export_xml.php
// Exporta la lista de usuarios o resultados en formato XML

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth_admin.php';

// Solo admins pueden exportar
requireAdmin();

require_once __DIR__ . '/db.php';

try {
    $db = getDB();

    $type = $_GET['type'] ?? 'users';

    if ($type === 'results') {
        $stmt = $db->query('
            SELECT tr.id, u.name, u.email, tr.topic, tr.score, tr.total, tr.taken_at
            FROM test_results tr
            JOIN users u ON u.id = tr.user_id
            ORDER BY tr.taken_at DESC
        ');
        $rows = $stmt->fetchAll();
        $rootTag = 'resultados';
        $itemTag = 'resultado';
        $filename = 'moveo_resultados_' . date('Y-m-d') . '.xml';
    } else {
        $stmt = $db->query('
            SELECT id, name, email, birthdate, goals, streak, last_test_date, created_at
            FROM users
            ORDER BY created_at DESC
        ');
        $rows = $stmt->fetchAll();
        $rootTag = 'usuarios';
        $itemTag = 'usuario';
        $filename = 'moveo_usuarios_' . date('Y-m-d') . '.xml';
    }

    // Construir XML
    $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><{$rootTag}/>");

    foreach ($rows as $row) {
        $item = $xml->addChild($itemTag);
        foreach ($row as $key => $value) {
            // Limpiar nombres de columna para XML válido
            $cleanKey = preg_replace('/[^a-zA-Z0-9_]/', '_', $key);
            $item->addChild($cleanKey, htmlspecialchars($value ?? '', ENT_XML1, 'UTF-8'));
        }
    }

    // Enviar cabeceras HTTP para descarga XML
    header('Content-Type: application/xml; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Access-Control-Allow-Origin: *');

    echo $xml->asXML();

} catch (PDOException $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => 'Error al exportar XML']);
} catch (Exception $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => 'Error al generar XML']);
}
