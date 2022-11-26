<?php
// GET: params: all or id=[number]
// receive: JSON

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

// Create JSON Result variable
$json_result = array();

// Get all headers
$headers = apache_request_headers();

if (!isset($headers['Authorization'])) {
    $json_result["code"] = 401;
    $json_result["message"] = "Login Required.";
    echo $json_result;
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

// Check if single read or read all
if (isset($_GET['all'])) {
    // Check if user has authorization
    if ($access > 1) {
        $json_result["code"] = 401;
        $json_result["message"] = "Unauthorized.";
        echo json_encode($json_result);
        die();
    }

    // Users query
    $result = $user->read();

    // Get row count
    $num = $result->rowCount();

    // Check if any users
    if ($num > 0) {
        // Post Array
        $json_result['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $user_item = array(
                'id' => $user_id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'credit_limit' => $credit_limit,
                'balance' => $balance,
                'created' => $created,
            );

            // Push to "data"
            array_push($json_result['data'], $user_item);
        }

        // Turn to JSON & Output
        echo json_encode($json_result);
    } else {
        // No posts
        $json_result["message"] = "No Users found.";
        echo json_encode(
            $json_result
        );
    }
} else {
    // Get ID
    $user->id = isset($_GET['id']) ? intval($_GET['id']) : die();

    // Check if user has authorization
    if (!$access > 1 || $user->id != $id) {
        $json_result["code"] = 401;
        $json_result["message"] = "Unauthorized.";
        echo json_encode($json_result);
        die();
    }

    // Get information
    $user->read_single();

    if (!isset($user->email)) {
        $json_result["message"] = "No Users found.";
        echo json_encode($json_result);
        die();
    }

    // Create array
    $json_result = array(
        'id' => intval($user->id),
        'firstname' => $user->firstname,
        'lastname' => $user->lastname,
        'email' => $user->email,
        'phone' => $user->phone,
        'address' => $user->address,
        'credit_limit' => $user->credit_limit,
        'balance' => $user->balance,
        'created' => $user->created,
    );

    // Make JSON
    echo json_encode($json_result);
}
