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
        $query = 'INSERT INTO ' . $this->table . ' 
        SET
         name = :name,
         description = :description,
         price = :price,
         supply = :supply,
         img_link = :img_link';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->name = Str::sanitizeString($this->name);
        $this->description = Str::sanitizeString($this->description);
        $this->price = Str::sanitizeDouble($this->price);
        $this->supply = Str::sanitizeInt($this->supply);
        $this->img_link = Str::sanitizeString($this->img_link);

        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':supply', $this->supply);
        $stmt->bindParam(':img_link', $this->name);

        if ($stmt->execute()) {
            if (isset($this->book)) {
                // 1. Create book record
                // 2. Create authors record
                // 3. Create genre record
                // 4. Associate each authors and genres to the book

                $this->book->create();
                $this->book->create_authors();
                $this->book->create_genres();
                $this->book->create_associations();
            } else {
                // 1. Create categories record
                // 2. Associate category to product
            }
        }
        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    /**
     * Note: $this->categories must be an array of categories formatted by handler.php
     * data sanitation will be performed here using Str::toUpperCamelCase
     * 
     * Returns: true if success, false if fail
     * Populates: $this->categories with an array of id associated to the inserted genres
     */
    public function create_categories()
    {
        $query = "INSERT IGNORE INTO " . $this->category_table . "(`name`)";
    }

    /**
     * Note: This function checks if category array contains numbers.
     * It then checks if an association is already made between current products object
     * and product_category. If there is no association, it will create one.
     */
    public function create_associations()
    {
    }
}
