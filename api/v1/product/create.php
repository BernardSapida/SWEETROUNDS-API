<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Product.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        $product = new Product();

        // Process the data
        $product->setName($data["name"]);
        $product->setFlavor($data["flavor"]);
        $product->setType($data["type"]);
        $product->setPrice($data["price"]);
        $product->save();

        // Send a response
        echo sendResponse(true, 'Successfully created a new product!');
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>