<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/UserInformation.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Process the data
        $user = new UserInformation();

        // Get user list
        $users = $user::getUsersInformation();

        // Send a response
        header('Content-Type: application/json');
        echo sendResponse(true, 'Successfully retrieve user accounts!', $users);
    }

    function sendResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => $data);
        return json_encode($response);
    }
?>