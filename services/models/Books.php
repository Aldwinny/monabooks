<?php

class Books
{
    private $conn;

    // Put table here
    private $table = 'books';
    private $authors_list_table = 'authors_list';
    private $authors_table = 'authors';
    private $genres_list_table = 'genres_list';
    private $genres_table = 'genres';

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
        $query = 'SELECT books.*, GROUP_CONCAT(DISTINCT author.name) authors, GROUP_CONCAT(DISTINCT genre.name) genres FROM ' . $this->table . '
        books JOIN ' . $this->authors_list_table . ' auth ON books.book_id = auth.book_id JOIN ' .
            $this->authors_table . ' author ON auth.author_id = author.author_id JOIN ' . $this->genres_list_table .
            ' gen ON books.book_id = gen.book_id JOIN ' . $this->genres_table . ' genre ON gen.genre_id = genre.genre_id GROUP BY books.book_id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    public function read_by_genre()
    {
        $query = 'SELECT books.*, GROUP_CONCAT(DISTINCT author.name) authors, GROUP_CONCAT(DISTINCT genre.name) genres FROM ' . $this->table . '
        books JOIN ' . $this->authors_list_table . ' auth ON books.book_id = auth.book_id JOIN ' .
            $this->authors_table . ' author ON auth.author_id = author.author_id JOIN ' . $this->genres_list_table .
            ' gen ON books.book_id = gen.book_id JOIN ' . $this->genres_table . ' genre ON gen.genre_id = genre.genre_id WHERE genre.name IN (?) GROUP BY books.book_id';

        // Define a replacer that changes the query based on number of items in genres variable
        $replacer = "";

        for ($i = 0; $i < count($this->genres); $i++) {
            $replacer = $replacer . "?, ";
        }
        $replacer = substr($replacer, 0, -2);

        // Replace query with new query
        $query = str_replace("?", $replacer, $query);

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind every element in array to query
        for ($i = 0; $i < count($this->genres); $i++) {
            $stmt->bindParam($i + 1, $this->genres[$i]);
        }



        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Reserved function
    public function read_by_publisher()
    {
        return null;
    }

    // Reserved function
    public function read_by_author()
    {
        return null;
    }

    public function read_single()
    {
        $query = 'SELECT books.*, GROUP_CONCAT(DISTINCT author.name) authors, GROUP_CONCAT(DISTINCT genre.name) genres FROM ' . $this->table . '
                 books JOIN ' . $this->authors_list_table . ' auth ON books.book_id = auth.book_id JOIN ' .
            $this->authors_table . ' author ON auth.author_id = author.author_id JOIN ' . $this->genres_list_table .
            ' gen ON books.book_id = gen.book_id JOIN ' . $this->genres_table . ' genre ON gen.genre_id = genre.genre_id WHERE books.book_id = :bid GROUP BY books.book_id LIMIT 0,1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(":bid", $this->id);

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

        // Set non-inherent Properties
        $this->authors = $row['authors'];
        $this->genres = $row['genres'];
    }

    public function get_genres()
    {
        $query = 'SELECT * FROM ' . $this->genres_table;

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    public function get_authors()
    {
        $query = 'SELECT * FROM ' . $this->authors_table;

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    public function get_publishers()
    {
        $query = 'SELECT DISTINCT publishers FROM ' . $this->table;

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }
}
