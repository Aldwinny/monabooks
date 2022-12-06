<?php
class Item
{
    private $conn;

    // Put table here
    private $table = 'cart_items';

    // Properties
    public $cart_item_id;
    public $cart_id;
    public $product_id;
    public $quantity;


    // Constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = 'SELECT * FROM ' . $this->table;

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    public function read_set()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE cart_id = ?';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }
}
