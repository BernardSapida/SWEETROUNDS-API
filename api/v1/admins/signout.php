<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Admin.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $admin = new Admin();
        $current_admin = $admin::loadByEmail($data["email"]);

        if($current_admin) {
            // Set status to inactive
            $current_admin->setOnlineStatus('Offline');
            $current_admin->save();
            
            // Send a response
            echo sendResponse(true, 'Successfully signed out!');
        } else {
            // Send a response
            echo sendResponse(false, 'Invalid parameters!');
        }
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>