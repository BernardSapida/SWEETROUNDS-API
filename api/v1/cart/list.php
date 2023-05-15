<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Cart.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        $user_id = $data['id'];
        
        $cart = new Cart();
        $user_cart = $cart->loadById($user_id);

        // Get list of messages
        $cart_items = $user_cart->getItems();

        // Send a response
        echo sendResponse(true, 'Successfully retrieve cart!', $cart_items);
    }

    function sendResponse($success, $message, $data) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => json_decode($data));
        return json_encode($response);
    }
?>