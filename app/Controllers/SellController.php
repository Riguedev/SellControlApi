<?php

namespace App\Controllers;

use App\Models\Sell;
use App\Core\Auth;

class SellController
{
    public function create()
    {
        Auth::authenticate(); // Proteger endpoint usando Core\Auth

        $input = json_decode(file_get_contents('php://input'), true);

        $quantity = $input['quantity'] ?? null;
        $date = $input['date'] ?? null;
        $user_id = $input['user_id'] ?? null;

        if (!$quantity || !$date || !$user_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Cantidad, fecha y usuario son requeridos']);
            return;
        }

        $sellModel = new Sell($quantity, $date, $user_id);

        if ($sellModel->create()) {
            http_response_code(201);
            echo json_encode(['status' => 'success', 'message' => 'Venta creada exitosamente', 'id' => $sellModel->getId()]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear la venta']);
        }
    }
}
