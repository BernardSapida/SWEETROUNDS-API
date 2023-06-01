<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/OrderItems.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //! Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        $order = new OrderItems();

        // Process the data
        $order->setQuantity($data["quantity"]);
        $order->setOrderId($data["order_id"]);
        $order->setProductId($data["product_id"]);
        $order->save();

        // Send a response
        echo sendResponse(true, 'Successfully added donut to order items!');
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>