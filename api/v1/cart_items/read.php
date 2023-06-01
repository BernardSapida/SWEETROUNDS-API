<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/CartItems.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //! Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        $user_id = $data['user_id'];
        
        $cart = new CartItems();

        // Get list of messages
        $cart_items = $cart->getUserCarts($user_id);

        // Send a response
        echo sendResponse(true, 'Successfully retrieve cart!', $cart_items);
    }

    function sendResponse($success, $message, $cart_items) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => $cart_items);
        return json_encode($response);
    }
?>