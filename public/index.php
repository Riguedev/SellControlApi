<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Pecee\SimpleRouter\SimpleRouter;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Definir el namespace de los controladores
SimpleRouter::setDefaultNamespace('\App\Controllers');

// Rutas de la API
SimpleRouter::post('/SellControl/public/login', 'UserController@login');
SimpleRouter::post('/SellControl/public/users', 'UserController@create');
SimpleRouter::post('/SellControl/public/sell/create', 'SellController@create');

// Iniciar el enrutamiento
SimpleRouter::start();
