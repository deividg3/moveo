<?php
// backend/functions.php
// Funciones reutilizables cargadas en todos los endpoints

/**
 * Envía cabeceras CORS estándar
 */
function cors_headers(): void {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
}

/**
 * Respuesta JSON unificada
 */
function json_response(array $data, int $code = 200): void {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Sanitiza un string de entrada: trim + htmlspecialchars
 */
function sanitize_input(string $value): string {
    $value = trim($value);
    $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    $value = strip_tags($value);
    return $value;
}

/**
 * Sanitiza un email con filter_var
 */
function sanitize_email(string $email): string {
    $email = trim($email);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    return $email;
}

/**
 * Valida formato de email
 */
function validate_email(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Capitaliza la primera letra de cada palabra en nombre/apellido
 * "david perez garcia" → "David Perez Garcia"
 */
function capitalize_name(string $name): string {
    $name = mb_strtolower(trim($name), 'UTF-8');
    // mb_convert_case maneja acentos y caracteres especiales
    return mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');
}

/**
 * Lee y decodifica el body JSON de una petición POST/PUT
 */
function read_json_body(): ?array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : null;
}

/**
 * Requiere que el método HTTP sea uno de los indicados
 */
function require_method(string ...$methods): void {
    if (!in_array($_SERVER['REQUEST_METHOD'], $methods)) {
        json_response(['error' => 'Method not allowed'], 405);
    }
}
