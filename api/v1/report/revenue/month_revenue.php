<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Report.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $report = new CashierReport();

        // Get user list
        $transactions = $report::getMonthRevenue($data["id"], $data["year"], $data["month"]);

        // Send a response
        echo sendResponse(true, 'Successfully retrieve month revenue!', $transactions);
    }

    function sendResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => $data);
        return json_encode($response);
    }
?>