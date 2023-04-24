<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Admin.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Process the data
        $admin = new Admin();

        // Get user list
        $admins = $admin::getAdmins();

        // Send a response
        header('Content-Type: application/json');
        echo sendResponse(true, 'Successfully retrieve admin accounts!', $admins);
    }

    function sendResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => $data);
        return json_encode($response);
    }
?>