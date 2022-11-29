<?php
class Products
{
    private $conn;

    // Put table here
    private $table = 'products';
    private $attr_table = 'product_attributes';
    private $category_table = 'product_categories';

    // Properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $supply;
    public $img_link;

    // Non-inherent Properties
    public $categories;
    public $book;


    // Constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all products
    public function read()
    {
        $query = 'SELECT p.*, GROUP_CONCAT(DISTINCT cat.category_name) category_name FROM ' . $this->table . ' p JOIN ' . $this->attr_table . ' attr ON p.product_id = attr.product_id JOIN ' . $this->category_table . ' cat ON attr.category_id = cat.category_id GROUP BY p.product_id';


        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    public function read_single()
    {
        $query = 'SELECT p.*, GROUP_CONCAT(DISTINCT cat.category_name) category_name FROM ' . $this->table . ' p JOIN ' . $this->attr_table . ' attr ON p.product_id = attr.product_id JOIN ' . $this->category_table . ' cat ON attr.category_id = cat.category_id WHERE p.product_id = ? GROUP BY p.product_id';

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
        $this->id = $row['product_id'];
        $this->name = $row['name'];
        $this->description = $row['description'];
        $this->price = $row['price'];
        $this->supply = $row['supply'];
        $this->img_link = $row['img_link'];

        // Set non-inherent properties
        $this->categories = $row['category_name'];
    }

    public function read_categories()
    {
        $query = "SELECT * FROM " . $this->category_table;

        // Prepare the query
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    public function create()
    {
        return false;
    }
}
