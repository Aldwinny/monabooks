<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/User.php';
include_once '../../utils/token.php';

// Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

// Instantiate user object
$user = new User($db);

// Create JSON Result variable
$json_result = array();

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->firstname, $data->lastname, $data->email, $data->phone, $data->phone, $data->address, $data->password)) {
    $json_result["status"] = 400;
    $json_result["message"] = "Incomplete credentials.";
    echo json_encode($json_result);
    die();
}

$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->phone = $data->phone;
$user->address = $data->address;
$user->credit_limit = $data->credit_limit;
$user->balance = $data->balance;
$user->password = $data->password;

// Create user
if ($user->create()) {
    $json_result["status"] = 201;
    $json_result["message"] = "User created successfully.";
    $json_result["token"] = Token::getToken($user->id, $user->firstname, $user->lastname, $user->access_level);
    echo json_encode($json_result);
} else {
    $json_result["status"] = 500;
    $json_result["message"] = "User not created. An error may have been the cause.";
    echo json_encode($json_result);
}
