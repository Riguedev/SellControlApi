<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Configuración básica de cabeceras para API
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Aquí iría el enrutamiento
echo json_encode([
    'status' => 'success',
    'message' => 'API de SellControl operativa'
]);
