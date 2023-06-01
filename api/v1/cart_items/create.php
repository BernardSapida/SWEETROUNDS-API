<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/CartItems.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //! Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        $cart = new CartItems();

        // Process the data
        $cart->setQuantity(1);
        $cart->setProductId($data["product_id"]);
        $cart->setUserId($data["user_id"]);
        $cart->save();

        // Send a response
        echo sendResponse(true, 'Successfully added donut to cart!');
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>