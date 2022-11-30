<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Book.php';
include_once '../../utils/token.php';

// Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

// Instantiate user object
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

if ($access > 1) {
    $json_result["code"] = 401;
    $json_result["message"] = "Unauthorized.";
    echo json_encode($json_result);
    die();
}

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->title, $data->publisher, $data->book_type, $data->cover_type, $data->genres, $data->authors)) {
    $json_result["code"] = 400;
    $json_result["message"] = "Bad Request: Incomplete data input.";
    echo json_encode($json_result);
    die();
}

// Assign values
$book->title = $data->title;
$book->publisher = $data->publisher;
$book->book_type = $data->book_type;
$book->cover_type = $data->cover_type;
$book->ed = $data->ed ?? 1;

// Assign non-inherent values
$book->genres = explode(",", $data->genres);
$book->authors = explode(",", $data->authors);

// Create book
if ($book->create() && $book->create_authors() && $book->create_genres() && $book->create_associations()) {
    $json_result["status"] = 201;
    $json_result["message"] = "Book created successfully.";
    echo json_encode($json_result);
} else {
    $json_result["status"] = 500;
    $json_result["message"] = "Internal Server Error: Book creation failed.";
    echo json_encode($json_result);
}
