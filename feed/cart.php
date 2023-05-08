<?php
    require_once realpath(dirname(__FILE__) . "/../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../")."/model/Cart.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        header('Content-Type: application/json');

        foreach($data["data"] as $userCart) {
            $cart = new Cart();

            // Process the data
            $cart->setItems(json_encode($userCart["items"]));
            $cart->setUserId($userCart["user_id"]);
            $cart->save();

            // Send a response
            echo sendResponse(true, 'Successfully saved cart!');
        }
    }

    function sendResponse($success, $message) {
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>