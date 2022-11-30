<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET,POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Product.php';
include_once '../../utils/token.php';

// Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

// Instantiate product object
$product = new Product($db);

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
    $product->id = $_GET['id'];

    $product->read_single();

    // If no product is found
    if (!isset($product->name)) {
        $json_result["code"] = 404;
        $json_result["message"] = "Not found: the product that has been requested does not exist.";
        echo json_encode($json_result);
        die();
    }

    // Set all values
    $product->name = $data->name ?? $product->name;
    $product->description = $data->description ?? $product->description;
    $product->price = doubleval($data->price ?? $product->price);
    $product->supply = intval($data->supply ?? $product->supply);
    $product->img_link = $data->img_link ?? $product->img_link;

    // TODO: RESERVED FOR FUTURE USE
    $product->categories = explode(',', $data->categories ?? $product->categories);
    $product->book = $data->book ?? $product->book;

    if ($product->update()) {
        $json_result["code"] = 200;
        $json_result["message"] = "Success: Product updated!";
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

/**
 * Other plans:
 * 1. Update categories if theyre explicitly given
 * 2. Update book association (optional)
 * 3. Delete a product
 */
