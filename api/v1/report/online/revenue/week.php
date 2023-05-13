<?php
    require_once realpath(dirname(__FILE__) . "/../../../../../")."/model/OrderReport.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $report = new OrderReport();

        // Get user list
        $revenue = $report::getWeekRevenue($data["year"], $data["month"], $data["week"]);

        // Send a response
        echo sendResponse(true, 'Successfully retrieve week revenue!', $revenue);
    }

    function sendResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => $data);
        return json_encode($response);
    }
?>