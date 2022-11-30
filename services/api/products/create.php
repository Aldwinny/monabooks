<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Products.php';
include_once '../../models/Books.php';
include_once '../../utils/token.php';

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

if ($access > 1) {
    $json_result["code"] = 401;
    $json_result["message"] = "Unauthorized.";
    echo json_encode($json_result);
    die();
}

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->name, $data->description, $data->price, $data->supply, $data->categories)) {
    $json_result["code"] = 400;
    $json_result["message"] = "Bad Request: Incomplete data input.";
    echo json_encode($json_result);
    die();
}

$product->name = $data->name;
$product->description = $data->description;
$product->price = $data->price;
$product->supply = $data->supply;
$product->img_link = $data->img_link ?? null;

// Must be a set of words divided by comma
$product->categories = explode(',', $data->categories);

// Book data is either set or empty
if (isset($data->book)) {
    // Instantiate a book
    $book = new Books($db);

    // Check if an id exists. Resolve the book and create a product
    if (isset($data->book->id)) {
        $book->id = $data->book->id;

        $book->read_single();

        // If book is not found
        if (!isset($book->name)) {
            $json_result["code"] = 404;
            $json_result["message"] = "Not found: Book requested for product conversion not found.";
            echo json_encode($json_result);
            die();
        }

        // If book is found, resolve all data to product
        // TO BE IMPLEMENTED
        $json_result["code"] = 501;
        $json_result["message"] = "Will be Implemented soon";
        echo json_encode($json_result);
        die();
    }

    // Check if an book data exists
    if (!isset($data->book->title, $data->book->publisher, $data->book->book_type, $data->book->cover_type, $data->book->authors, $data->book->genres)) {
        $json_result["code"] = 400;
        $json_result["message"] = "Bad Request: Incomplete data input.";
        echo json_encode($json_result);
        die();
    }

    $book->title = $data->book->title;
    $book->publisher = $data->book->publisher;
    $book->book_type = $data->book->book_type;
    $book->cover_type = $data->book->cover_type;
    $book->ed = $data->book->ed ?? 1;

    $book->authors = explode(',', $data->book->authors);
    $book->genres = explode(',', $data->book->genres);

    // Assign book instance to product book variable.
    $product->book = $book;

    // Create product
    if ($product->create()) {
        $json_result["code"] = 200;
        $json_result["message"] = "Book created successfully.";
        echo json_encode($json_result);
        die();
    } else {
        echo json_encode(array("message" => "product not created"));
        $json_result["code"] = 500;
        $json_result["message"] = "Internal Server Error: Book Product not created";
        echo json_encode($json_result);
        die();
    }
} else {
    // Create product
    if ($product->create()) {
        $json_result["code"] = 200;
        $json_result["message"] = "Product created successfully.";
        echo json_encode($json_result);
        die();
    } else {
        $json_result["code"] = 500;
        $json_result["message"] = "Internal Server Error: Product not created";
        echo json_encode($json_result);
        die();
    }
}
