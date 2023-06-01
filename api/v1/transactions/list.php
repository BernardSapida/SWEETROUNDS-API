<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Transaction.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        //! Process the data
        $transaction = new Transaction();

        // Get user list
        $transactions = $transaction::getTransactions();

        // Send a response
        header('Content-Type: application/json');
        echo sendResponse(true, 'Successfully retrieve transaction list!', $transactions);
    }

    function sendResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => $data);
        return json_encode($response);
    }
?>