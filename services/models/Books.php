<?php

class Books
{
    private $conn;

    // Put table here
    private $table = 'books';

    // Properties
    public $id;
    public $title;
    public $publisher;
    public $book_type;
    public $cover_type;
    public $ed;

    // Non-inherent Properties
    public $authors;
    public $genres;

    // Constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all books
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
        $query = 'SELECT * FROM ' . $this->table . 'WHERE book_id = ? LIMIT 0,1';

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
        $this->title = $row['title'];
        $this->publisher = $row['publisher'];
        $this->book_type = $row['book_type'];
        $this->cover_type = $row['cover_type'];
        $this->ed = $row['ed'];
    }
}
