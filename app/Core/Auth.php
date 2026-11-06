<?php

namespace App\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth
{
    /**
     * Valida el token JWT del encabezado Authorization.
     * Retorna el payload del token si es válido.
     * Termina la ejecución si es inválido.
     */
    public static function authenticate(): array
    {
        $jwtKey = $_ENV["JWT_KEY"] ?? '';
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? null;

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(['error' => 'Usuario no autenticado']);
            exit;
        }

        $token = $matches[1];

        try {
            return (array) JWT::decode($token, new Key($jwtKey, 'HS256'));
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Token inválido o expirado']);
            exit;
        }
    }
}
