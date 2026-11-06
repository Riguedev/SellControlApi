<?php

namespace App\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use App\Core\Auth;

class UserController
{
    public function create()
    {
        // Registro no requiere Auth
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
        // Login no requiere Auth
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
            $payload = [
                'iat' => time(),
                'exp' => time() + (60 * 60),
                'sub' => $user['id'] ?? null
            ];

            $jwt = JWT::encode($payload, $_ENV["JWT_KEY"], 'HS256');

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

    public function profile()
    {
        $userData = Auth::authenticate(); // Autenticación usando el Core\Auth
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Acceso autorizado',
            'user_id' => $userData['sub']
        ]);
    }
}
