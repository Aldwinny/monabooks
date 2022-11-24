<?php

class Books
{
    private $conn;

    // Put table here
    private $table = 'products';

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
}
