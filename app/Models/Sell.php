<?php

namespace App\Models;

use App\Config\Database;

class Sell
{
    private $id;
    private $quantity;
    private $date;
    private $user_id;

    public function __construct($quantity = null, $date = null, $user_id = null, $id = null)
    {
        $this->id = $id;
        $this->quantity = $quantity;
        $this->date = $date;
        $this->user_id = $user_id;
    }

    /**
     * Guarda la venta en la base de datos
     */
    public function create()
    {
        $db = Database::getInstance()->getConnection();
        $sql = "INSERT INTO sells (quantity, date, user_id) VALUES (:quantity, :date, :user_id)";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':user_id', $this->user_id);
        
        if ($stmt->execute()) {
            $this->id = $db->lastInsertId();
            return true;
        }
        
        return false;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getQuantity() { return $this->quantity; }
    public function getDate() { return $this->date; }
    public function getUserId() { return $this->user_id; }

    // Setters
    public function setQuantity($quantity) { $this->quantity = $quantity; }
    public function setDate($date) { $this->date = $date; }
    public function setUserId($user_id) { $this->user_id = $user_id; }
}
