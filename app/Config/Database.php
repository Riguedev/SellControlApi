<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    // Configuración de la base de datos (puedes mover estos valores a un .env más adelante)
    private string $host = 'localhost';
    private string $db_name = 'sellcontrol';
    private string $username = 'root';
    private string $password = '';

    private function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            $this->connection = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    // Evitar clonación y serialización
    private function __clone() {}
    public function __wakeup() {
        throw new \Exception("No se puede deserializar una instancia Singleton.");
    }
}
