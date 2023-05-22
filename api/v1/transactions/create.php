<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Transaction.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        $transaction = new Transaction();

        // Process the data for order
        $transaction->setInvoiceId($data["invoice_id"]);
        $transaction->setNote($data["note"]);
        $transaction->setTax($data["tax"]);
        $transaction->setDiscount($data["discount"]);
        $transaction->setAdminId($data["admin_id"]);
        $transaction->save();
        
        $transaction_id = $transaction->getId();

        // Send a response
        echo sendResponse(true, 'Successfully place a transaction!', $transaction_id);
    }

    function sendResponse($success, $message, $transaction_id) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'transaction_id' => $transaction_id);
        return json_encode($response);
    }
?>