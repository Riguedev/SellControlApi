<?php

namespace App\Controllers;

use App\Models\User;
use Pecee\SimpleRouter\SimpleRouter as Router;
use Firebase\JWT\JWT;

class UserController
{
    private string $jwtKey;

    public function __construct()
    {
        $this->jwtKey = $_ENV["JWT_KEY"] ?? '';
    }

    public function create()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $name = $input['name'] ?? null;
        $email = $input['email'] ?? null;
        $password = $input['password'] ?? null;

        if (!$name || !$email || !$password) {
            http_response_code(400);
            echo json_encode(['error' => 'Nombre, email y contraseña son requeridos']);
            return;
        }

        $userModel = new User();
        
        // Verificar si el usuario ya existe
        if ($userModel->findByEmail($email)) {
            http_response_code(409);
            echo json_encode(['error' => 'El email ya está registrado']);
            return;
        }

        $result = $userModel->create($name, $email, $password);

        if ($result) {
            http_response_code(201);
            echo json_encode(['status' => 'success', 'message' => 'Usuario creado exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear el usuario']);
        }
    }

    public function login()
    {
        // Obtener datos del JSON de la request
        $input = json_decode(file_get_contents('php://input'), true);

        $email = $input['email'] ?? null;
        $password = $input['password'] ?? null;

        if (!$email || !$password) {
            http_response_code(400);
            echo json_encode(['error' => 'Email y contraseña son requeridos']);
            return;
        }

        $userModel = new User();
        $user = $userModel->login($email, $password);

        if ($user) {
            // Generar el Token JWT
            $payload = [
                'iat' => time(), // Issued at
                'exp' => time() + (60 * 60), // Expira en 1 hora
                'sub' => $user['id'] ?? null // ID del usuario
            ];

            $jwt = JWT::encode($payload, $this->jwtKey, 'HS256');

            echo json_encode([
                'status' => 'success',
                'user' => $user,
                'token' => $jwt
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Credenciales inválidas']);
        }
    }
}
