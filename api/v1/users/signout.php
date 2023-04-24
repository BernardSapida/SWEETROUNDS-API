<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/User.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $user = new User();
        $current_user = $user::loadByEmail($data["email"]);

        if($current_user) {
            // Set status to inactive
            $current_user->setStatus('inactive');
            $current_user->save();
            
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