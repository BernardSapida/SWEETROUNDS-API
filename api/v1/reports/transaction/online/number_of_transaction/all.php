<?php
    require_once realpath(dirname(__FILE__) . "/../../../../../../")."/model/OrderReport.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $report = new OrderReport();

        // Get user list
        $transactions = $report::getAllTransactions();

        // Send a response
        echo sendResponse(true, 'Successfully retrieve report transactions!', $transactions);
    }

    function sendResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'number_of_transaction' => $data);
        return json_encode($response);
    }
?>