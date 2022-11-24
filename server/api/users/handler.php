<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/User.php';

// Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

// Instantiate user object
$user = new User($db);

// Check if single read or read all
if (isset($_GET['all'])) {
    /**
     * WHEN USING ALL, THERE MUST BE AN IDENTIFIER THAT CERTIFIES USER AS EMPLOYEE/ADMIN
     */

    // Users query
    $result = $user->read();

    // Get row count
    $num = $result->rowCount();

    // Check if any users
    if ($num > 0) {
        // Post Array
        $users_arr = array();
        $users_arr['data'] = array();

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
            array_push($users_arr['data'], $user_item);
        }

        // Turn to JSON & Output
        echo json_encode($users_arr);
    } else {
        // No posts
        echo json_encode(
            array('message' => 'No Users Found')
        );
    }
} else {
    /**
     * WHEN USING SINGLE, USER CAN ONLY RETRIEVE THEIR OWN INFORMATION. ONLY ADMINS CAN RETRIEVE ALL
     */
    // Get ID
    $user->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get information
    $user->read_single();

    if (!isset($user->email)) {
        echo json_encode(array(
            "message" => "No Users Found."
        ));
        die();
    }

    // Create array
    $user_arr = array(
        'id' => $user->id,
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
    echo json_encode($user_arr);
}
