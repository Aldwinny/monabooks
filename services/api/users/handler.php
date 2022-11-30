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



// Check if user is attempting to login
if (isset($_GET['login'], $_POST['email'], $_POST['password'])) {
    $user->email = $_POST['email'];

    $success = $user->read_single_by_email();

    if ($success) {
        if (password_verify($_POST['password'], $user->password)) {
            $token = Token::getToken($user->id, $user->firstname, $user->lastname, $user->access_level);

            $json_result["code"] = 200;
            $json_result["message"] = "Login complete.";
            $json_result["token"] = $token;
            echo json_encode($json_result);
            die();
        } else {
            $json_result["code"] = 401;
            $json_result["message"] = "Incorrect Password.";
            echo json_encode($json_result);
            die();
        }
    } else {
        $json_result["code"] = 401;
        $json_result["message"] = "User account not found.";
        echo json_encode($json_result);
        die();
    }
}

// Check if user has a token
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
    $user->id = isset($_GET['id']) ? intval($_GET['id']) : -1;

    if ($user->id < 1) {
        $json_result["status"] = 404;
        $json_result["message"] = "The requested resource was not found on this server.";
        echo json_encode($json_result);
        die();
    }

    // Check if user has authorization
    if (!($access > 1 || $user->id != $id)) {
        $json_result["code"] = 401;
        $json_result["message"] = "Unauthorized.";
        echo json_encode($json_result);
        die();
    }

    // Get information
    $user->read_single();

    if (!isset($user->email)) {
        $json_result["code"] = 404;
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
        'img_link' => $user->img_link,
        'balance' => $user->balance,
        'created' => $user->created,
    );

    // Make JSON
    echo json_encode($json_result);
}
