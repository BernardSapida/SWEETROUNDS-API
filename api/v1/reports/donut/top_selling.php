<?php
    require_once realpath(dirname(__FILE__) . "/../../../../")."/model/Product.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        //! Process the data
        $report = new Product();

        // Get user list
        $topDonut = $report::loadTop10Donut();

        // Send a response
        echo sendResponse(true, 'Successfully retrieve report transactions!', $topDonut);
    }

    function sendResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => $data);
        return json_encode($response);
    }
?>