<?php
    require_once realpath(dirname(__FILE__) . "/../../../../../../")."/model/Order.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //! Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $order = new Order();

        // Get donut sold from week
        $donutSold = $order::getMonthDonutSold($data["year"], $data["month"]);

        // Send a response
        echo sendResponse(true, 'Successfully retrieve donut total sold report!', $donutSold);
    }

    function sendResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, "total_sold" => $data);
        return json_encode($response);
    }
?>