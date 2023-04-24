<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/User.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/helpers/hash.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $user = new User();
        $current_user = $user::loadByEmail($data["email"]);

        if($current_user) {
            // Verify user password in database
            $verifyPassword = verifyPassword($data["password"], $current_user->getPassword());

            if($verifyPassword === true) {
                // Send a response
                echo sendResponse(true, 'Successfully signed in!', $current_user->getUserDetails());
            } else {
                // Send a response
                echo sendResponse(true, 'Password is incorrect!');
            }
        } else {
            // Send a response
            echo sendResponse(false, 'Email address doesn\'t exist!');
        }
    }

    function sendResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, "data" => $data);
        return json_encode($response);
    }
?>