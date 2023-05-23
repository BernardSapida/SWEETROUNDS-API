<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/CartItems.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postData = file_get_contents('php://input');
        $data = json_decode($postData, true);

        $cart = new CartItems();
        $cart->deleteCartDonut($data['cart_id']);

        // Send a response
        echo sendResponse(true, 'Successfully remove donut from cart!');
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>