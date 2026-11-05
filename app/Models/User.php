<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Valida las credenciales de usuario para el inicio de sesión
     */
    public function login(string $email, string $password): array|false
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Retornamos el usuario (sin la contraseña por seguridad)
            unset($user['password']);
            return $user;
        }

        return false;
    }

    /**
     * Ejemplo de creación de usuario
     */
    public function create(string $name, string $email, string $password): bool
    {
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT) // Siempre hashear contraseñas
        ]);
    }

    /**
     * Ejemplo de búsqueda por email
     */
    public function findByEmail(string $email): array|false
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        
        return $stmt->fetch();
    }
}
