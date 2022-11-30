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
         supply = :supply';

        if (isset($this->img_link)) {
            $query = $query . ", img_link = :img_link";
        }

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->name = Str::sanitizeString($this->name);
        $this->description = Str::sanitizeString($this->description);
        $this->price = Str::sanitizeDouble($this->price);
        $this->supply = Str::sanitizeInt($this->supply);

        if (isset($this->img_link)) {
            $this->img_link = Str::sanitizeString($this->img_link);
        }

        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':supply', $this->supply);

        if (isset($this->img_link)) {
            $stmt->bindParam(':img_link', $this->img_link);
        }

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
            }
            // 1. Resolve ID & img_link
            // 2. Create categories record
            // 3. Associate category to product

            $ask = "SELECT * FROM " . $this->table . ' WHERE name = ?';

            // Prepare query
            $ask_stmt = $this->conn->prepare($ask);

            // Bind params
            $ask_stmt->bindParam(1, $this->name);

            // Execute Query
            $ask_stmt->execute();

            if ($ask_stmt->rowCount() == 0) {
                return false;
            }

            $row = $ask_stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->id = $row['product_id'];
            $this->img_link = $row['img_link'];

            $this->create_categories();
            $this->create_associations();
            return true;
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
        $query = "INSERT IGNORE INTO " . $this->category_table . "(`category_name`) VALUES ";

        // Create multiple values query if categories is many
        if (count($this->categories) > 1) {
            for ($i = 0; $i < count($this->categories); $i++) {
                $query = $query . "(:name" . strval($i) . "), ";
            }
            $query = substr($query, 0, -2);
        } else {
            $query = $query . "(:name0)";
        }

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        for ($i = 0; $i < count($this->categories); $i++) {
            $stmt->bindValue(':name' . strval($i), Str::toUpperCamelCase($this->categories[$i]));
        }

        // Execute query and turn categories variable to array of IDs
        if ($stmt->execute()) {
            $q = 'SELECT * FROM ' . $this->category_table . ' WHERE category_name IN (?)';

            $replacer = "";
            // Prepare the query
            for ($i = 0; $i < count($this->categories); $i++) {
                $replacer = $replacer . "?, ";
            }

            $replacer = substr($replacer, 0, -2);
            $q = str_replace("?", $replacer, $q);

            // Prepare the statement
            $stmt2 = $this->conn->prepare($q);

            // Bind data
            for ($i = 0; $i < count($this->categories); $i++) {
                $stmt2->bindParam($i + 1, $this->categories[$i]);
            }

            // Execute query
            $stmt2->execute();

            $num = $stmt2->rowCount();

            // Retrieve results and assign as numbers
            if ($num > 0) {
                $this->categories = array();

                // For every category value, push its id to authors
                while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    array_push($this->categories, $row['category_id']);
                }
            } else {
                return false;
            }
            return true;
        }


        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    /**
     * Note: This function checks if category array contains numbers.
     * It then checks if an association is already made between current products object
     * and product_category. If there is no association, it will create one.
     */
    public function create_associations()
    {
        if (!isset($this->categories)) {
            return false;
        }

        if ($this->categories == array_filter($this->categories, 'is_numeric')) {
            $query_categories_list = 'SELECT DISTINCT * FROM ' . $this->attr_table . ' 
            WHERE
             product_id = ?';

            // Prepare statement
            $categ_stmt = $this->conn->prepare($query_categories_list);

            // Bind parameters
            $categ_stmt->bindParam(1, $this->id);

            // Execute Query
            $categ_stmt->execute();

            // If there are no associations, create one.
            // Otherwise, check if associations match that of array
            if ($categ_stmt->rowCount() == 0) {
                $categ_assocs = $this->categories;
            } else {
                $categ_comparator = array();
                while ($row = $categ_stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($categ_comparator, $row['category_id']);
                }

                // Leave only those that will be associated
                $categ_assocs = array_diff($this->categories, $categ_comparator);
            }

            if (!count($categ_assocs) == 0) {
                $categ_assocs_query = 'INSERT INTO ' . $this->attr_table . '(`product_id`, `category_id`, `book_id`) VALUES ';

                for ($i = 0; $i < count($categ_assocs); $i++) {
                    $categ_assocs_query = $categ_assocs_query . '(:product_id' . $i . ', :category_id' . $i . ', :book_id' . $i . '), ';
                }

                // Finalize query
                $categ_assocs_query = substr($categ_assocs_query, 0, -2);

                // Prepare statement
                $categ_assocs_stmt = $this->conn->prepare($categ_assocs_query);

                // Bind data
                for ($i = 0; $i < count($categ_assocs); $i++) {
                    $categ_assocs_stmt->bindParam(":product_id" . $i, $this->id);

                    if (!isset($this->book->id)) {
                        $categ_assocs_stmt->bindValue(":book_id" . $i, null, PDO::PARAM_NULL);
                    } else {
                        $categ_assocs_stmt->bindValue(":book_id" . $i, $this->book->id);
                    }
                    $categ_assocs_stmt->bindParam(":category_id" . $i, $this->categories[$i]);
                }

                $categ_assocs_stmt->execute();
            }
            return true;
        }
    }
}
