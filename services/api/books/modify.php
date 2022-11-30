<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET,POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Book.php';
include_once '../../utils/token.php';

// Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

// Instantiate book object
$book = new Book($db);

// Create JSON Result variable
$json_result = array();

// Get all headers
$headers = apache_request_headers();

// If no token, return 401
// If token broken, return 400
if (!isset($headers['Authorization'])) {
    $json_result["code"] = 401;
    $json_result["message"] = "Login Required.";
    echo json_encode($json_result);
    die();
}

// Get token payload from token
$token = explode(" ", $headers['Authorization'])[1];

// Check token validity
if (!Token::verifyToken($token)) {
    $json_result["code"] = 400;
    $json_result["message"] = "Bad Token, Please login again.";
    echo json_encode($json_result);
    die();
}

$payload = json_decode(Token::getPayload($token), true);

// Separate information from payload
$access = intval($payload["access_level"]);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Only employee and admin can edit
if ($access > 1) {
    $json_result["code"] = 401;
    $json_result["message"] = "Unauthorized.";
    echo json_encode($json_result);
    die();
}

if (isset($_GET['id'])) {
    $book->id = $_GET['id'];

    $book->read_single();

    // If no book is found
    if (!isset($book->title)) {
        $json_result["code"] = 404;
        $json_result["message"] = "Not found: the book that has been requested does not exist.";
        echo json_encode($json_result);
        die();
    }

    // Check if request was to delete
    if (isset($_GET['delete'])) {
        // Wrap in try catch and catch all constraint error
        try {
            $book->delete();
        } catch (PDOException $e) {
            if (intval($e->getCode()) == 23000) {
                $json_result["code"] = 400;
                $json_result["message"] = "Bad Request: A foreign key constraint is preventing the deletion of selected item. Delete the product connected to this book before proceeding.";
                echo json_encode($json_result);
                die();
            } else {
                $json_result["code"] = 500;
                $json_result["message"] = "Internal Server Error: An error has occurred while processing delete query.";
                echo json_encode($json_result);
                die();
            }
        }
        $json_result["code"] = 200;
        $json_result["message"] = "Success: Book successfully deleted.";
        echo json_encode($json_result);
        die();
    }

    // Set all values
    $book->title = $data->title ?? $book->title;
    $book->publisher = $data->publisher ?? $book->publisher;
    $book->book_type = $data->book_type ?? $book->book_type;
    $book->cover_type = $data->cover_type ?? $book->cover_type;
    $book->ed =  intval($data->ed ?? $book->ed);

    // Set non-inherent properties
    // TODO: RESERVED FOR FUTURE USE
    $book->authors = explode(",", $data->authors ?? $book->authors);
    $book->genres = explode(",", $data->genres ?? $book->genres);

    if ($book->update()) {
        if (isset($data->authors, $data->genres)) {
            $book->delete_all_associations();
            $book->create_authors();
            $book->create_genres();
            $book->create_associations();
        } else if (isset($data->authors)) {
            $book->delete_authors_associations();
            $book->create_authors();
            $book->create_associations();
        } else if (isset($data->genres)) {
            $book->delete_genres_associations();
            $book->create_genres();
            $book->create_associations();
        }
        $json_result["code"] = 200;
        $json_result["message"] = "Success: Book updated!";
        echo json_encode($json_result);
        die();
    } else {
        $json_result["code"] = 500;
        $json_result["message"] = "Internal Server Error: An error has occurred";
        echo json_encode($json_result);
        die();
    }
}

$json_result["code"] = 400;
$json_result["message"] = "Bad Request";
echo json_encode($json_result);
die();
