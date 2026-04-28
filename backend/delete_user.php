<?php
// backend/delete_user.php
// CRUD — Delete: Eliminar un usuario por su ID

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';

cors_headers();
require_method('DELETE', 'POST');

$data = read_json_body();
if (!$data) {
    json_response(['error' => 'JSON inválido'], 400);
}

$userId = (int)($data['user_id'] ?? 0);

if ($userId <= 0) {
    json_response(['error' => 'user_id requerido'], 422);
}

try {
    $db = getDB();

    // Verificar que el usuario existe antes de borrar
    $check = $db->prepare('SELECT id FROM users WHERE id = :id LIMIT 1');
    $check->execute([':id' => $userId]);

    if (!$check->fetch()) {
        json_response(['error' => 'Usuario no encontrado'], 404);
    }

    // Borrar el usuario (ON DELETE CASCADE borra test_results asociados)
    $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
    $stmt->execute([':id' => $userId]);

    json_response([
        'success' => true,
        'message' => 'Usuario eliminado correctamente',
    ]);

} catch (PDOException $e) {
    json_response(['error' => 'Error interno del servidor'], 500);
}
