<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/helpers/hash.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Order.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/OrderDetail.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        $orderDetail = new OrderDetail();
        $order = new Order();

        // Process the data for order detail
        $orderDetail->setFirstname($data["firstname"]);
        $orderDetail->setLastname($data["lastname"]);
        $orderDetail->setAddressLine1($data["address_line_1"]);
        $orderDetail->setAddressLine2($data["address_line_2"]);
        $orderDetail->setCity($data["city"]);
        $orderDetail->setContact($data["contact"]);
        $orderDetail->save();

        $order_detail_id = $orderDetail->getId();


        // Process the data for order
        $order->setOrderNumber(getRandomId());
        $order->setItems($data["items"]);
        $order->setNote($data["note"]);
        $order->setTax($data["tax"]);
        $order->setShippingFee($data["shipping_fee"]);
        $order->setDiscount($data["discount"]);
        $order->setTotal($data["total"]);
        $order->setOrderStatus("Pending");
        $order->setPaymentStatus("Pending");
        $order->setUserId($data["user_id"]);
        $order->setUserInfoId($order_detail_id);
        $order->save();

        // Send a response
        echo sendResponse(true, 'Successfully place an order!');
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>