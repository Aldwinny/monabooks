<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Products.php';
include_once '../../models/Books.php';

// Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

// Instantiate product object
$product = new Products($db);

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
$id = intval($payload["user_id"]);
$access = intval($payload["access_level"]);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

$product->name = $data->name;
$product->description = $data->description;
$product->price = $data->price;
$product->supply = $data->supply;
$product->img_link = $data->img_link;

// Must be a set of words divided by comma
$product->categories = $data->categories;

// Book data is either set or empty
if (isset($data->book)) {
    // Instantiate a book
    $book = new Books($db);

    $book->title = $data->title;
    $book->publisher = $data->publisher;
    $book->book_type = $data->book_type;
    $book->cover_type = $data->cover_type;
    $book->ed = $data->ed;

    $book->authors = explode(',', $data->authors);
    $book->genres = explode(',', $data->genres);

    // Assign book instance to product book variable.
    $product->book = $book;

    // Create product
    if ($product->create()) {
        echo json_encode(array("message" => "product Created"));
    } else {
        echo json_encode(array("message" => "product not created"));
    }
} else {
    // Create product
    if ($product->create()) {
        echo json_encode(array("message" => "product Created"));
    } else {
        echo json_encode(array("message" => "product not created"));
    }
}
