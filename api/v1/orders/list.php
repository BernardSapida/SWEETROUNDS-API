<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Order.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        //! Process the data
        $order = new Order();

        // Get user list
        $orders = $order::getOrders();

        // Send a response
        header('Content-Type: application/json');
        echo sendResponse(true, 'Successfully retrieve user orders!', $orders);
    }

    function sendResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => $data);
        return json_encode($response);
    }
?>