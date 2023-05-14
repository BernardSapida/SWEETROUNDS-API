<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Order.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $order = new Order();

        // Get user list
        $current_order = $order::loadById($data["id"]);

        // echo json_encode($current_order->getOrderHistoryDetails());

        if($current_order) {
            $current_order->setOrderStatus($data["order_status"]);
            $current_order->setPaymentStatus($data["payment_status"]);
            $current_order->save();

            // Send a response
            echo sendResponse(true, 'Successfully updated orders status!');
        } else {
            echo sendResponse(false, 'Invalid parameters');
        }
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>