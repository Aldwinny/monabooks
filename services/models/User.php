<?php

include '../../utils/string.php';
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
        $this->balance = floatval($row['balance']);
        $this->credit_limit = intval($row['credit_limit']);
        $this->created = $row['created'];
        $this->img_link = $row['img_link'];
        $this->access_level = intval($row['access_level']);
        $this->password = $row['password'];
    }

    public function read_single_by_email()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE email = ? LIMIT 0,1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(1, $this->email);

        // Execute Query
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return false;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->id = $row['user_id'];
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->email = $row['email'];
        $this->phone = $row['phone'];
        $this->address = $row['address'];
        $this->balance = floatval($row['balance']);
        $this->credit_limit = intval($row['credit_limit']);
        $this->created = $row['created'];
        $this->img_link = $row['img_link'];
        $this->access_level = intval($row['access_level']);
        $this->password = $row['password'];
        return true;
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
        $this->firstname = Str::sanitizeString($this->firstname);
        $this->lastname = Str::sanitizeString($this->lastname);
        $this->email = Str::sanitizeEmail($this->email);
        $this->phone = Str::sanitizeInt($this->phone);
        $this->address = Str::sanitizeString($this->address);
        $this->credit_limit = Str::sanitizeInt($this->credit_limit);
        $this->balance = Str::sanitizeDouble($this->balance);

        // Hash + Salt password
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

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
            $this->setProperties();
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    // UNUSED AND UNTESTED
    public function update()
    {

        $query = 'UPDATE ' . $this->table . ' SET 
        firstname = :firstname,
        lastname = :lastname,
        email = :email,
        phone = :phone,
        address = :address,
        balance = :balance,
        img_link = :img_link,
        credit_limit = :credit_limit,
        access_level = :access_level WHERE user_id = :id';

        // Clean data
        $this->firstname = Str::sanitizeString($this->firstname);
        $this->lastname = Str::sanitizeString($this->lastname);
        $this->email = Str::sanitizeEmail($this->email);
        $this->phone = Str::sanitizeInt($this->phone);
        $this->address = Str::sanitizeString($this->address);
        $this->credit_limit = Str::sanitizeInt($this->credit_limit);
        $this->img_link = Str::sanitizeString($this->img_link);
        $this->balance = Str::sanitizeDouble($this->balance);

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind data
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':balance', $this->balance);
        $stmt->bindParam(':credit_limit', $this->credit_limit);
        $stmt->bindParam(':img_link', $this->img_link);
        $stmt->bindParam(':access_level', $this->access_level);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    private function setProperties()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE email= :email';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind data
        $stmt->bindParam(':email', $this->email);

        // Execute Query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->id = intval($row['user_id']);
        $this->img_link = $row['img_link'];
        $this->created = $row['created'];
        $this->access_level = $row['access_level'];
    }

    public function delete()
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE user_id = ?';

        // Prepare the query
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(1, $this->id);

        // Execute query and return True or False;
        return $stmt->execute();
    }
}
