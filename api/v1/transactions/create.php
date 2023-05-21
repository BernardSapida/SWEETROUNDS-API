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
        $transaction->setItems($data["items"]);
        $transaction->setDonutQuantity($data["donut_quantity"]);
        $transaction->setNote($data["note"]);
        $transaction->setTax($data["tax"]);
        $transaction->setDiscount($data["discount"]);
        $transaction->setTotal($data["total"]);
        $transaction->setAdminId($data["admin_id"]);
        $transaction->save();

        // Send a response
        echo sendResponse(true, 'Successfully place a transaction!');
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>