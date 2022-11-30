<?php
// GET: params: all or id=[number]
// receive: JSON

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

if (isset($_GET['all'])) {

    $result = $product->read();
    // Get row count
    $num = $result->rowCount();

    // Check if any products
    if ($num > 0) {
        // Post Array
        $json_result['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $product_item = array(
                'id' => intval($product_id),
                'name' => $name,
                'price' => doubleval($price),
                'supply' => intval($supply),
                'img_link' => $img_link,
                'categories' => $category_name,
            );

            // Push to "data"
            array_push($json_result['data'], $product_item);
        }
        echo json_encode($json_result);
    } else {
        $json_result["status"] = 404;
        $json_result["message"] = "The requested resource was not found on this server.";
        echo json_encode($json_result);
        die();
    }
} else if (isset($_GET['categories'])) {
    $result = $product->read_categories();
    // Get row count
    $num = $result->rowCount();

    // Check if any categories exist
    if ($num > 0) {
        // Post Array
        $json_result['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $product_category_item = array(
                'category_id' => intval($category_id),
                'category_name' => $category_name,
                'category_description' => $category_description,
            );

            // Push to "data"
            array_push($json_result['data'], $product_category_item);
        }
        echo json_encode($json_result);
    } else {
        $json_result["status"] = 404;
        $json_result["message"] = "The requested resource was not found on this server.";
        echo json_encode($json_result);
        die();
    }
} else {
    $product->id = isset($_GET['id']) ? intval($_GET['id']) : -1;

    // Check if id was provided, otherwise send a 404
    if ($product->id < 1) {
        $json_result["status"] = 404;
        $json_result["message"] = "The requested resource was not found on this server.";
        echo json_encode($json_result);
        die();
    }

    $product->read_single();

    if (!isset($product->name)) {
        $json_result["status"] = 404;
        $json_result["message"] = "No Products found.";
        echo json_encode($json_result);
        die();
    }

    $json_result = array(
        'id' => intval($product->id),
        'name' => $product->name,
        'price' => doubleval($product->price),
        'supply' => intval($product->supply),
        'img_link' => $product->img_link,
        'categories' => $product->categories,
    );

    if (isset($product->book->title)) {
        $json_result['book'] = array(
            'title' => $product->book->title,
            'publisher' => $product->book->publisher,
            'book_type' => $product->book->book_type,
            'cover_type' => $product->book->cover_type,
            'ed' => $product->book->ed,
            'authors' => $product->book->authors,
            'genres' => $product->book->genres,
        );
    }

    echo json_encode($json_result);
}
