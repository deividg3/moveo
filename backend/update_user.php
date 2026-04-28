<?php
// backend/update_user.php
// CRUD — Update: Actualizar datos de un usuario

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';

cors_headers();
require_method('PUT', 'POST');

$data = read_json_body();
if (!$data) {
    json_response(['error' => 'JSON inválido'], 400);
}

$userId   = (int)($data['user_id'] ?? 0);
$name     = isset($data['name']) ? sanitize_input($data['name']) : null;
$email    = isset($data['email']) ? sanitize_email($data['email']) : null;
$avatar   = isset($data['avatar']) ? (int)$data['avatar'] : null;
$goals    = $data['goals'] ?? null;

if ($userId <= 0) {
    json_response(['error' => 'user_id requerido'], 422);
}

// Construir la query dinámica con solo los campos proporcionados
$fields = [];
$params = [':id' => $userId];

if ($name !== null && $name !== '') {
    $name = capitalize_name($name);
    $fields[] = 'name = :name';
    $params[':name'] = $name;
}

if ($email !== null && $email !== '') {
    if (!validate_email($email)) {
        json_response(['error' => 'Email no válido'], 422);
    }
    $fields[] = 'email = :email';
    $params[':email'] = $email;
}

if ($avatar !== null) {
    $fields[] = 'avatar = :avatar';
    $params[':avatar'] = $avatar;
}

if ($goals !== null) {
    $goalsStr = is_array($goals) ? implode(',', $goals) : sanitize_input($goals);
    $fields[] = 'goals = :goals';
    $params[':goals'] = $goalsStr;
}

if (empty($fields)) {
    json_response(['error' => 'No hay campos para actualizar'], 422);
}

try {
    $db = getDB();
    $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
    $stmt = $db->prepare($sql);
    $stmt->execute($params);

    if ($stmt->rowCount() === 0) {
        json_response(['error' => 'Usuario no encontrado'], 404);
    }

    json_response([
        'success' => true,
        'message' => 'Usuario actualizado correctamente',
    ]);

} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        json_response(['error' => 'El correo ya está en uso'], 409);
    }
    json_response(['error' => 'Error interno del servidor'], 500);
}
