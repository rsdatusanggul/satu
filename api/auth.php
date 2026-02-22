<?php
/**
 * SATU DATU SANGGUL - Authentication API
 * Endpoint for password verification to access internal services
 *
 * Method: POST
 * Content-Type: application/json
 *
 * Request:
 * {
 *   "password": "your_password"
 * }
 *
 * Response (Success):
 * {
 *   "success": true,
 *   "token": "session_token_here",
 *   "expires_at": "2025-01-01T12:00:00Z"
 * }
 *
 * Response (Error):
 * {
 *   "success": false,
 *   "message": "Password salah"
 * }
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Use POST.'
    ]);
    exit();
}

// Load environment variables
$envFile = __DIR__ . '/../.env';
$env = [];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $env[trim($name)] = trim($value);
    }
}

// Get password from environment or use default
$correctPassword = isset($env['INTERNAL_PASSWORD']) ? $env['INTERNAL_PASSWORD'] : 'datu123';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$password = isset($input['password']) ? $input['password'] : '';

// Verify password
if (password_verify($password, $correctPassword) || $password === $correctPassword) {
    // Generate session token
    $token = bin2hex(random_bytes(32));
    $expiresAt = date('c', strtotime('+1 hour'));

    // In production, store session in database or Redis
    // For now, return success

    echo json_encode([
        'success' => true,
        'token' => $token,
        'expires_at' => $expiresAt
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Password salah'
    ]);
}
