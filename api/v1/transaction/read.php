<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Transaction.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $transaction = new Transaction();
        $current_transaction = $transaction::loadById($data["id"]);

        if($current_transaction) {
            // Get transaction information
            $transactionInformation = $current_transaction->getTransaction();

            // Send a response
            echo sendResponse(true, 'Transaction retrieve successfully!', $transactionInformation);
        } else {
            // Send a response
            echo sendResponse(false, 'Transaction doesn\'t exist!');
        }
    }

    function sendResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, "data" => $data);
        return json_encode($response);
    }
?>