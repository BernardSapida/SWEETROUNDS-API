<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/validation/create_user.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/User.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $user = new User();
        $current_user = $user::loadById($data["user_id"]);
        $validPassword = validPassword($data["password"]);
        $validConfirmPassword = validPasswordConfirmation($data["password"], $data["confirmPassword"]);

        if($current_user) {
            if(!$validPassword) {
                echo sendResponse(false, 'Password is invalid!');
            } else if(!$validConfirmPassword) {
                echo sendResponse(false, 'Password and confirm password didn\'t matched!');
            } else {
                $current_user->setPassword($data['password'] ?? $current_user->getPassword());
                $current_user->save();
    
                // Send a response
                echo sendResponse(true, 'Successfully password updated!');
            }
        } else {
            // Send a response
            echo sendResponse(false, 'User account didn\'t exist!');
        }
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>