<?php
    require_once realpath(dirname(__FILE__) . "/../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../")."/helpers/hash.php";
    require_once realpath(dirname(__FILE__) . "/../")."/model/Order.php";
    require_once realpath(dirname(__FILE__) . "/../")."/model/OrderDetail.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        header('Content-Type: application/json');

        foreach($data["data"] as $userOrder) {
            $orderDetail = new OrderDetail();
            $order = new Order();

            // Process the data for order detail
            $orderDetail->setFirstname($userOrder["firstname"]);
            $orderDetail->setLastname($userOrder["lastname"]);
            $orderDetail->setAddressLine1($userOrder["address_line_1"]);
            $orderDetail->setAddressLine2($userOrder["address_line_2"]);
            $orderDetail->setCity($userOrder["city"]);
            $orderDetail->setContact($userOrder["contact"]);
            $orderDetail->save();

            $order_detail_id = $orderDetail->getId();

            // Process the user order
            $order->setOrderNumber(getRandomId());
            $order->setItems(json_encode($userOrder["items"]));
            $order->setDonutQuantity($userOrder["donut_quantity"]);
            $order->setNote($userOrder["note"]);
            $order->setTax($userOrder["tax"]);
            $order->setShippingFee($userOrder["shipping_fee"]);
            $order->setDiscount($userOrder["discount"]);
            $order->setTotal($userOrder["total"]);
            $order->setOrderStatus("Pending");
            $order->setPaymentStatus("Pending");
            $order->setUserId($userOrder["user_id"]);
            $order->setUserInfoId($order_detail_id);
            $order->save();

            // Send a response
            echo sendResponse(true, 'Successfully place an order!');
        }
    }

    function sendResponse($success, $message) {
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>