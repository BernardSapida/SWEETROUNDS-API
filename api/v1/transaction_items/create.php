<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/TransactionItems.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //! Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        $transaction_items = new TransactionItems();

        // Process the data for order
        $transaction_items->setQuantity($data["quantity"]);
        $transaction_items->setTransactionId($data["transaction_id"]);
        $transaction_items->setProductId($data["product_id"]);
        $transaction_items->save();

        // Send a response
        echo sendResponse(true, 'Successfully place a transaction items!');
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>