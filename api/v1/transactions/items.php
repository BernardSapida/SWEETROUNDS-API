<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Transaction.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //! Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $transaction = new Transaction();

        // Get user list
        $transactions = $transaction::getTransactionItems($data["transaction_id"]);

        // Send a response
        header('Content-Type: application/json');
        echo sendResponse(true, 'Successfully retrieve transaction items!', $transactions);
    }

    function sendResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => $data);
        return json_encode($response);
    }
?>