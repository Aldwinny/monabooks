<?php
class Cart
{
    private $conn;

    // Put table here
    private $table = 'carts';

    // Properties
    public $cart_id;
    public $user_id;
    public $settled;
    public $invoice_id;

    // Item buffer
    public $item;


    // Constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
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

    public function read_all_single()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE user_id = ? LIMIT 0,1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(1, $this->user_id);

        // Execute Query
        $stmt->execute();

        return $stmt;
    }

    public function read_single()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE user_id = ? AND settled = 0 LIMIT 0,1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(1, $this->user_id);

        // Execute Query
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->cart_id = $row['cart_id'];
        $this->user_id = $row['user_id'];
        $this->settled = $row['settled'];
        $this->invoice_id = $row['invoice_id'];
    }

    // Adds a single item to the cart
    public function add()
    {
    }

    // Removes an item in the cart
    public function remove()
    {
    }

    // Add/Updates an item in the cart
    public function update()
    {
    }

    // Clears the cart
    public function clear()
    {
    }

    // DELETES the cart
    public function delete()
    {
    }

    // SETTLES THE CART (CREATES INVOICE)
    public function settle()
    {
    }
}
