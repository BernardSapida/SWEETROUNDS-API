<?php
    require_once realpath(dirname(__FILE__) . "/../../../../../../")."/model/OrderReport.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //! Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $report = new OrderReport();

        // Get user list
        $completed_transaction = $report::getWeekCompletedOrders($data["year"], $data["week"]);

        // Send a response
        echo sendResponse(true, 'Successfully retrieve completed transaction report!', $completed_transaction);
    }

    function sendResponse($success, $message, $completed_transaction = 0) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'completed_transaction' => $completed_transaction);
        return json_encode($response);
    }
?>