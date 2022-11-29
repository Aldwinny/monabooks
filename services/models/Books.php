<?php

include_once '../utils/string.php';

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

    public function create()
    {
        $query = 'INSERT INTO ' . $this->table . '
        SET
         title = :title,
         publisher = :publisher,
         book_type = :book_type,
         cover_type = :cover_type,
         ed = :ed';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->title = Str::sanitizeString($this->title);
        $this->publisher = Str::sanitizeString($this->publisher);
        $this->book_type = Str::sanitizeString($this->book_type);
        $this->cover_type = Str::sanitizeString($this->cover_type);
        $this->ed = Str::sanitizeInt($this->ed);

        // Bind data
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':publisher', $this->publisher);
        $stmt->bindParam(':book_type', $this->book_type);
        $stmt->bindParam(':cover_type', $this->cover_type);
        $stmt->bindParam(':ed', $this->ed);

        if ($stmt->execute()) {
            $q = 'SELECT * FROM ' . $this->table . 'WHERE title = :title AND publisher = :publisher AND ed = :ed';

            // Prepare statement
            $stmt2 = $this->conn->prepare($q);

            // Bind data
            $stmt2->bindParam(':title', $this->title);
            $stmt2->bindParam(':publisher', $this->publisher);
            $stmt2->bindParam(':ed', $this->ed);

            // Execute query and retrieve result
            $stmt2->execute();
            $row = $stmt2->fetch(PDO::FETCH_ASSOC);

            // Set ID property
            $this->id = intval($row['book_id']);

            return true;
        }
        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    /**
     * Note: $this->authors must be an array of authors formatted by handler.php
     * data sanitation will be performed here using Str::toUpperCamelCase
     * 
     * Returns: true if success, false if fail
     * Populates: $this->authors with an array of id associated to the inserted authors
     */
    public function create_authors()
    {
        $query = 'INSERT IGNORE INTO ' . $this->authors_table . ' (`name`) VALUES ';

        // Create multiple values query if authors is many
        if (count($this->authors) > 1) {
            for ($i = 0; $i < count($this->authors); $i++) {
                $query = $query . "(:name" . strval($i) . "), ";
            }
            $query = substr($query, 0, -2);
        } else {
            $query = $query . "(:name0)";
        }

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        for ($i = 0; $i < count($this->authors); $i++) {
            $stmt->bindParam(':name' . strval($i), Str::toUpperCamelCase($this->authors[$i]));
        }

        // Execute query and turn authors variable to array of IDs
        if ($stmt->execute()) {
            $q = 'SELECT * FROM ' . $this->authors_table . 'WHERE name IN (?)';

            $replacer = "";
            // Prepare the query
            for ($i = 0; $i < count($this->authors); $i++) {
                $replacer = $replacer . "?, ";
            }

            $replacer = substr($replacer, 0, -2);
            $q = str_replace("?", $replacer, $q);

            // Prepare statement
            $stmt2 = $this->conn->prepare($q);

            // Bind data
            for ($i = 0; $i < count($this->authors); $i++) {
                $stmt2->bindParam($i + 1, $this->authors[$i]);
            }

            // Execute query
            $stmt2->execute();

            $num = $stmt2->rowCount();

            // Retrieve results and assign as numbers
            if ($num > 0) {
                $this->authors = array();

                // For every author value, push its id to authors
                while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    array_push($this->authors, $row['author_id']);
                };
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
     * Note: $this->genres must be an array of genres formatted by handler.php
     * data sanitation will be performed here using Str::toUpperCamelCase
     * 
     * Returns: true if success, false if fail
     * Populates: $this->genres with an array of id associated to the inserted genres
     */
    public function create_genres()
    {
        $query = "INSERT IGNORE INTO " . $this->genres_table . " (`name`) VALUES ";

        // Create multiple values query if genres is many
        if (count($this->genres) > 1) {
            for ($i = 0; $i < count($this->genres); $i++) {
                $query = $query . "(:name" . strval($i) . "), ";
            }
            $query = substr($query, 0, -2);
        } else {
            $query = $query . "(:name0)";
        }

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        for ($i = 0; $i < count($this->genres); $i++) {
            $stmt->bindParam(':name' . strval($i), Str::toUpperCamelCase($this->genres[$i]));
        }

        // Execute query
        if ($stmt->execute()) {
            $q = 'SELECT * FROM ' . $this->genres_table . 'WHERE name IN (?)';

            $replacer = "";
            // Prepare the query
            for ($i = 0; $i < count($this->genres); $i++) {
                $replacer = $replacer . "?, ";
            }

            $replacer = substr($replacer, 0, -2);
            $q = str_replace("?", $replacer, $q);

            // Prepare statement
            $stmt2 = $this->conn->prepare($q);

            // Bind data
            for ($i = 0; $i < count($this->genres); $i++) {
                $stmt2->bindParam($i + 1, $this->genres[$i]);
            }

            // Execute query
            $stmt2->execute();

            $num = $stmt2->rowCount();

            // Retrieve results and assign as numbers
            if ($num > 0) {
                $this->genres = array();

                // For every genre value, push its id to genres
                while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    array_push($this->genres, $row['genre_id']);
                };
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
     * Note: This function checks if either genres or authors arrays are numbers.
     * It then checks if an association is already made between current books object
     * and authors/genres. If there is no association, it will create one.
     */
    public function create_associations()
    {
        // Checks if either authors or genres have contents
        if (!(isset($this->authors) || isset($this->genres))) {
            return false;
        }

        // Checks if all contents in authors are numeric
        if ($this->authors == array_filter($this->authors, 'is_numeric')) {
            $query_authors_list = 'SELECT DISTINCT * FROM ' . $this->authors_list_table . ' 
            WHERE 
            book_id = ?';

            // Prepare statement
            $al_stmt = $this->conn->prepare($query_authors_list);

            // Bind parameters
            $al_stmt->bindParam(1, $this->id);

            // Execute Query
            $al_stmt->execute();

            // If there are no associations, create one.
            // Otherwise, check if associations match that of array
            if ($al_stmt->rowCount() == 0) {
                $al_assocs = $this->authors;
            } else {
                $al_comparator = array();
                while ($row = $al_stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($al_comparator, $row['author_id']);
                }

                // Leave only those that will be associated
                $al_assocs = array_diff($this->authors, $al_comparator);
            }
        }

        // Checks if all contents in genres are numeric
        if ($this->genres == array_filter($this->genres, 'is_numeric')) {
            $query_genres_list = 'SELECT DISTINCT * FROM ' . $this->genres_list_table . ' 
            WHERE 
            book_id = ?';

            // Prepare statement
            $gl_stmt = $this->conn->prepare($query_genres_list);

            // Bind parameters
            $gl_stmt->bindParam(1, $this->id);

            // Execute Query
            $gl_stmt->execute();

            // If there are no associations, create one.
            // Otherwise, check if associations match that of array
            if ($gl_stmt->rowCount() == 0) {
                $gl_assocs = $this->genres;
            } else {
                $gl_comparator = array();
                while ($row = $gl_stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($gl_comparator, $row['genre_id']);
                }

                // Leave only those that will be associated
                $gl_assocs = array_diff($this->genres, $al_comparator);
            }
        }

        // If there are items that can be associated in al_assocs array
        if (!(count($al_assocs) == 0)) {
            $al_assocs_query = 'INSERT INTO ' . $this->authors_list_table . ' (`book_id`, `author_id`) VALUES ';

            for ($i = 0; $i < count($al_assocs); $i++) {
                $al_assocs_query . '(:book_id' . $i . ', :author_id' . $i . '), ';
            }

            // Finalize query
            $al_assocs_query = substr($al_assocs_query, 0, -2);

            // Prepare statement
            $al_assocs_stmt = $this->conn->prepare($al_assocs_query);

            // Bind data
            for ($i = 0; $i < count($al_assocs); $i++) {
                $al_assocs_stmt->bindParam(':book_id' . $i, $this->id);
                $al_assocs_stmt->bindParam(':author_id' . $i, $this->authors[$i]);
            }

            $al_assocs_stmt->execute();
        }

        // If there are items that can be associated in al_assocs array
        if (!(count($gl_assocs) == 0)) {
            $gl_assocs_query = 'INSERT INTO ' . $this->genres_list_table . ' (`book_id`, `author_id`) VALUES ';

            for ($i = 0; $i < count($gl_assocs); $i++) {
                $gl_assocs_query . '(:book_id' . $i . ', :genre_id' . $i . '), ';
            }

            // Finalize query
            $gl_assocs_query = substr($gl_assocs_query, 0, -2);

            // Prepare statement
            $gl_assocs_stmt = $this->conn->prepare($gl_assocs_query);

            // Bind data
            for ($i = 0; $i < count($gl_assocs); $i++) {
                $gl_assocs_stmt->bindParam(':book_id' . $i, $this->id);
                $gl_assocs_stmt->bindParam(':genre_id' . $i, $this->genres[$i]);
            }

            $gl_assocs_stmt->execute();
        }
        return true;
    }
}
