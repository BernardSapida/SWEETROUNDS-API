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
        $orders = $order::searchOrder($data["search"], $data["start"]);
        $NPage = count($order::getSearchOrderNPage($data["search"]));

        // Send a response
        header('Content-Type: application/json');
        echo sendResponse(true, 'Successfully retrieve user orders!', $orders, $NPage);
    }

    function sendResponse($success, $message, $data = null, $NPage = 0) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => $data, 'size' => $NPage);
        return json_encode($response);
    }
?>