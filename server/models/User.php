<?php
class User
{
    private $conn;

    // Put table here
    private $table = 'users';

    // Properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $phone;
    public $address;
    public $balance;
    public $credit_limit;
    public $created;
    public $img_link;
    public $access_level;
    public $password;

    // Constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all users
    public function read()
    {
        $query = 'SELECT * FROM ' . $this->table;

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get single user based on id
    public function read_single()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE user_id = ? LIMIT 0,1';

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
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->email = $row['email'];
        $this->phone = $row['phone'];
        $this->address = $row['address'];
        $this->balance = $row['balance'];
        $this->credit_limit = $row['credit_limit'];
        $this->created = $row['created'];
        $this->img_link = $row['img_link'];
        $this->access_level = $row['access_level'];
    }

    public function create()
    {
        $query = 'INSERT INTO ' . $this->table . ' 
        SET
         firstname = :firstname,
         lastname = :lastname,
         email = :email,
         phone = :phone,
         address = :address,
         balance = :balance,
         credit_limit = :credit_limit,
         password = :password';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $this->phone = filter_var($this->phone, FILTER_SANITIZE_NUMBER_INT);
        $this->address = htmlspecialchars(strip_tags($this->lastname));
        $this->credit_limit = filter_var($this->credit_limit, FILTER_SANITIZE_NUMBER_INT);
        $this->balance = filter_var($this->balance, FILTER_SANITIZE_NUMBER_FLOAT);

        // Bind data
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':credit_limit', $this->credit_limit);
        $stmt->bindParam(':balance', $this->balance);
        $stmt->bindParam(':password', $this->password);

        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}
