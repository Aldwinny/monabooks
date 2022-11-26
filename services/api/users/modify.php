<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET,POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/User.php';
include_once '../../utils/token.php';

// Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

// Instantiate user object
$user = new User($db);

// If no token, return 401
// If token broken, return 400

// Create JSON Result variable
$json_result = array();

// Get all headers
$headers = apache_request_headers();

if (!isset($headers['Authorization'])) {
    $json_result["code"] = 401;
    $json_result["message"] = "Login Required.";
    echo json_encode($json_result);
    die();
}

// Get token payload from token
$token = explode(" ", $headers['Authorization'])[1];
$payload = json_decode(Token::getPayload($token), true);

// Separate information from payload
$id = $payload["user_id"];
$access = $payload["access_level"];

// Check token validity
if (!Token::verifyToken($token)) {
    $json_result["code"] = 400;
    $json_result["message"] = "Bad Token, Please login again.";
    echo json_encode($json_result);
    die();
}

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->password)) {
    $json_result["code"] = 401;
    $json_result["message"] = "Missing credentials! Password not found.";
    echo json_encode($json_result);
    die();
} else {
    $user->$id = $payload['user_id'];

    // Obtain user information
    $user->read_single();

    // Validate user password
    if ($user->password !== password_hash($data->password, PASSWORD_BCRYPT)) {
        $json_result["code"] = 401;
        $json_result["message"] = "Wrong credentials! Password incorrect!";
        echo json_encode($json_result);
        die();
    }
}

// SET all credentials & prepare for modification
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->phone = $data->phone;
$user->address = $data->address;
$user->credit_limit = $data->credit_limit;
$user->balance = $data->balance;
$user->password = $data->password;

if ($user->update()) {
    $json_result["code"] = 200;
    $json_result["message"] = "Data change request successful!";
    echo json_encode($json_result);
    die();
}
$json_result["code"] = 500;
$json_result["message"] = "An error has occurred!";
echo json_encode($json_result);
