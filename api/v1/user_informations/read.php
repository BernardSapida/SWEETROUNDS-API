<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/UserInformation.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $user = new UserInformation();
        $current_user = $user::loadById($data["user_id"]);

        if($current_user) {
            // Get user information
            $userInformation = $current_user->getUserInformation();

            // Send a response
            echo sendResponse(true, 'Account details retrieve successfully!', $userInformation);
        } else {
            // Send a response
            echo sendResponse(false, 'Account details doesn\'t exist!');
        }
    }

    function sendResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, "data" => $data);
        return json_encode($response);
    }
?>