<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/helpers/hash.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Order.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        $favorite = new Order();

        // Process the data
        $favorite->setOrderNumber(getRandomId());
        $favorite->setItems($data["items"]);
        $favorite->setNote($data["note"]);
        $favorite->setTax($data["tax"]);
        $favorite->setShippingFee($data["shipping_fee"]);
        $favorite->setDiscount($data["discount"]);
        $favorite->setTotal($data["total"]);
        $favorite->setOrderStatus($data["order_status"]);
        $favorite->setPaymentStatus($data["payment_status"]);
        $favorite->setUserId($data["user_id"]);
        $favorite->setUserInfoId($data["user_info_id"]);
        $favorite->save();

        // Send a response
        echo sendResponse(true, 'Successfully place an order!');
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>