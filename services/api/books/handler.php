<?php
// GET: params: all or id=[number]
// receive: JSON

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET,POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Books.php';
include_once '../../utils/token.php';

// Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

// Instantiate book object
$book = new Books($db);

// Create JSON Result variable
$json_result = array();

if (isset($_GET['all'])) {
    $result = $book->read();

    // Get row count
    $num = $result->rowCount();

    // Check if any books
    if ($num > 0) {
        // Post Array
        $json_result['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $book_item = array(
                'id' => intval($book_id),
                'title' => $title,
                'publisher' => $publisher,
                'book_type' => $book_type,
                'cover_type' => $cover_type,
                'ed' => intval($ed),
                'authors' => $authors,
                'genres' => $genres,
            );

            // Push to "data"
            array_push($json_result['data'], $book_item);
        }
        echo json_encode($json_result);
    } else {
        $json_result["status"] = 404;
        $json_result["message"] = "The requested resource was not found on this server.";
        echo json_encode($json_result);
        die();
    }
} else if (isset($_GET['id'])) {
    $book->id = intval($_GET['id']);

    // Check if id was provided, otherwise send a 404
    if ($book->id < 1) {
        $json_result["status"] = 404;
        $json_result["message"] = "The requested resource was not found on this server.";
        echo json_encode($json_result);
        die();
    }

    $book->read_single();

    if (!isset($book->title)) {
        $json_result["status"] = 404;
        $json_result["message"] = "No books found.";
        echo json_encode($json_result);
        die();
    }

    $json_result = array(
        'id' => intval($book->id),
        'title' => $book->title,
        'publisher' => $book->publisher,
        'book_type' => $book->book_type,
        'cover_type' => $book->cover_type,
        'ed' => intval($book->ed),
        'authors' => $book->authors,
        'genres' => $book->genres,
    );

    echo json_encode($json_result);
} else if (isset($_GET['genres'])) {
    $genres = explode(",", $_GET['genres']);

    // Prepare books genres variable for binding
    if (count($genres) >= 1) {
        $book->genres = $genres;
    } else {
        $json_result["status"] = 404;
        $json_result["message"] = "No books found.";
        echo json_encode($json_result);
        die();
    }

    $result = $book->read_by_genre();

    // Get row count
    $num = $result->rowCount();

    if ($num > 0) {
        $json_result['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $product_item = array(
                'id' => intval($book_id),
                'title' => $title,
                'publisher' => $publisher,
                'book_type' => $book_type,
                'cover_type' => $cover_type,
                'ed' => intval($ed),
                'authors' => $authors,
            );

            // Push to "data"
            array_push($json_result['data'], $product_item);
        }
        echo json_encode($json_result);
    } else {
        $json_result["status"] = 404;
        $json_result["message"] = "The requested resource was not found on this server.";
        echo json_encode($json_result);
    }
}
