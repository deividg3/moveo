<?php
// backend/register.php
// Registro de nuevo usuario con sanitización completa

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';

cors_headers();
require_method('POST');

// ── Leer y validar body ─────────────────────────────────────────────────────
$data = read_json_body();

if (!$data) {
    json_response(['error' => 'JSON inválido'], 400);
}

// ── Sanitización de entrada ─────────────────────────────────────────────────
$name      = sanitize_input($data['name'] ?? '');
$email     = sanitize_email($data['email'] ?? '');
$password  = $data['password'] ?? '';
$birthdate = sanitize_input($data['birthdate'] ?? '');
$avatar    = (int)($data['avatar'] ?? 0);
$goals     = $data['goals'] ?? [];

// ── Validación ──────────────────────────────────────────────────────────────
if (!$name || !$email || !$password) {
    json_response(['error' => 'Faltan campos obligatorios (nombre, email, contraseña)'], 422);
}

if (!validate_email($email)) {
    json_response(['error' => 'Formato de email no válido'], 422);
}

if (strlen($password) < 6) {
    json_response(['error' => 'La contraseña debe tener al menos 6 caracteres'], 422);
}

// ── Capitalizar nombre (primera letra de cada palabra en mayúsculas) ────────
$name = capitalize_name($name);

// ── Hash de la contraseña ───────────────────────────────────────────────────
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// ── Sanitizar goals (si es array, convertir a CSV) ──────────────────────────
$goalsStr = is_array($goals)
    ? implode(',', array_map('sanitize_input', $goals))
    : sanitize_input($goals);

// ── Insertar usuario ────────────────────────────────────────────────────────
try {
    $db = getDB();

    $stmt = $db->prepare("
        INSERT INTO users (name, email, password_hash, birthdate, avatar, goals, created_at)
        VALUES (:name, :email, :password_hash, :birthdate, :avatar, :goals, NOW())
    ");

    $stmt->execute([
        ':name'          => $name,
        ':email'         => $email,
        ':password_hash' => $passwordHash,
        ':birthdate'     => $birthdate ?: null,
        ':avatar'        => $avatar,
        ':goals'         => $goalsStr,
    ]);

    $userId = $db->lastInsertId();

    json_response([
        'success' => true,
        'user_id' => $userId,
        'name'    => $name,
        'message' => 'Usuario registrado correctamente',
    ], 201);

} catch (PDOException $e) {
    // Duplicado de email (unique constraint)
    if ($e->getCode() === '23000') {
        json_response(['error' => 'El correo ya está registrado'], 409);
    }
    json_response(['error' => 'Error interno del servidor'], 500);
}