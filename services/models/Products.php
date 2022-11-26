<?php
class Products
{
    private $conn;

    // Put table here
    private $table = 'products';

    // Properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $supply;

    // Non-inherent Properties
    public $category;

    // Constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all products
    public function read()
    {
        $query = 'SELECT * FROM ' . $this->table;

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }
}
