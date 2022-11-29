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
    public $img_link;

    // Non-inherent Properties
    public $category_list;
    public $book;


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

    public function read_single()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE product_id = ? LIMIT 0,1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(1, $this->id);

        // Execute Query
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->name = $row['name'];
        $this->description = $row['description'];
        $this->price = $row['price'];
        $this->supply = $row['supply'];
        $this->img_link = $row['img_link'];

        // Set non-inherent properties

    }
}
