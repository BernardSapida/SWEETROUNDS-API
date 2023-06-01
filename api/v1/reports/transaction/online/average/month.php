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
        $average_sale = $report::getMonthAverageSale($data["year"], $data["month"]);

        // Send a response
        echo sendResponse(true, 'Successfully retrieve average sale report!', $average_sale);
    }

    function sendResponse($success, $message, $average_sale = 0) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'average_sale' => $average_sale);
        return json_encode($response);
    }
?>