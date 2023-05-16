<?php
    require_once realpath(dirname(__FILE__) . "/../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../")."/model/Transaction.php";
    require_once realpath(dirname(__FILE__) . "/../")."/helpers/hash.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        header('Content-Type: application/json');

        foreach($data["data"] as $userTransaction) {
            $transaction = new Transaction();

            // Process the data for order
            $transaction->setInvoiceId($userTransaction["invoice_id"]);
            $transaction->setItems(json_encode($userTransaction["items"]));
            $transaction->setNote($userTransaction["note"]);
            $transaction->setTax($userTransaction["tax"]);
            $transaction->setDiscount($userTransaction["discount"]);
            $transaction->setTotal($userTransaction["total"]);
            $transaction->setAdminId($userTransaction["admin_id"]);
            $transaction->save();

            // Send a response
            echo sendResponse(true, 'Successfully place a transaction!');
        }
    }

    function sendResponse($success, $message) {
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>