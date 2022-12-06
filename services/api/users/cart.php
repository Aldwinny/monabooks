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
include_once '../../models/Cart.php';
include_once '../../models/Item.php';
include_once '../../utils/token.php';

// Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

// Instantiate user object
$user = new User($db);
$cart = new Cart($db);
$cart->item = new Item($db);

// Create JSON Result variable
$json_result = array();

// Get all headers
$headers = apache_request_headers();

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

if (isset($_GET['all'])) {
    // Check if user has authorization
    if ($access > 1) {
        $json_result["code"] = 401;
        $json_result["message"] = "Unauthorized.";
        echo json_encode($json_result);
        die();
    }

    // Users query
    $result = $cart->read();

    // Get row count
    $num = $result->rowCount();

    // Check if any users
    if ($num > 0) {
        // Post Array
        $json_result['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $user_item = array(
                'cart_id' => $cart_id,
                'user_id' => $user_id,
                'settled' => $settled,
                'invoice_id' => $invoice_id,
            );

            // Push to "data"
            array_push($json_result['data'], $user_item);
        }

        // Turn to JSON & Output
        echo json_encode($json_result);
    } else {
        // No posts
        $json_result["code"] = 404;
        $json_result["message"] = "No Carts found.";
        echo json_encode(
            $json_result
        );
        die();
    }
}

// Get active user cart
if (isset($_GET['user'], $_GET['active'])) {
    // Check if user has authorization
    if ($access > 1) {
        $json_result["code"] = 401;
        $json_result["message"] = "Unauthorized.";
        echo json_encode($json_result);
        die();
    }

    $cart->user_id = intval($_GET['user']);
    $cart->read_single();


    if (!isset($cart->cart_id)) {
        $json_result["code"] = 404;
        $json_result["message"] = "No Carts found.";
        echo json_encode($json_result);
        die();
    }

    $json_result = array(
        'cart_id' => $cart->cart_id,
        'user_id' => $cart->user_id,
        'settled' => $cart->settled,
        'invoice_id' => $cart->invoice_id
    );

    // Make JSON
    echo json_encode($json_result);
    die();

    // GET ALL USER CART BASED ON USER ID
} else if (isset($_GET['user'])) {
    // Check if user has authorization
    if ($access > 1) {
        $json_result["code"] = 401;
        $json_result["message"] = "Unauthorized.";
        echo json_encode($json_result);
        die();
    }

    $cart->user_id = intval($_GET['user']);
    $result = $cart->read_all_single();

    // Get row count
    $num = $result->rowCount();

    if ($num > 0) {

        // Post Array
        $json_result['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $carts = array(
                'cart_id' => $cart_id,
                'user_id' => $user_id,
                'settled' => $settled,
                'invoice_id' => $invoice_id,
            );

            // Push to "data"
            array_push($json_result['data'], $carts);
        }

        // Turn to JSON & Output
        echo json_encode($json_result);
        die();
    } else {
        // No posts
        $json_result["code"] = 404;
        $json_result["message"] = "No Carts found.";
        echo json_encode(
            $json_result
        );
        die();
    }
}

// Admin tasks is get all cart o
// get list of carts based on user_id [GET?user] o
// get active cart based on user_id [GET?user&active] o
// remove cart with cart_id [GET?cart&remove] 

// TODO: REMOVE CART 
if (isset($_GET['remove'], $_GET['cart'])) {
    // Check if user has authorization
    if ($access > 1) {
        $json_result["code"] = 401;
        $json_result["message"] = "Unauthorized.";
        echo json_encode($json_result);
        die();
    }

    $cart->cart_id = intval($_GET['cart']);
    $cart->delete();
}

// ALL NORMAL USER BELOW

if (isset($_GET['add'])) {
    if (!isset($_GET['product'])) {
    }
}

if (isset($_GET['update'])) {
    if (!isset($_GET['product'])) {
    }
    if (isset($_GET['qty'])) {
    }
}

if (isset($_GET['remove'])) {
    if (!isset($_GET['product'])) {
    }
    if (isset($_GET['qty'])) {
    }
}

if (isset($_GET['clear'])) {
}


// Get cart will get cart based on user id [GET? with token]
// Add item will add 1 item based on product id [GET?add&product with token]
// Add item will add/update items based on product id [GET?update&product&qty with token]
// Remove item will remove items based on product id [GET?remove&product with token]
// clear cart