<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/validation/create_user.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/User.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        $validResponse = valid($data);

        // Process the data
        if($validResponse === true) {
            $user = new User();
            $user->setFullname($data['firstname'] . " " . $data['lastname']);
            $user->setEmail($data['email']);
            $user->setPassword($data['password']);
            $user->setAuthProvider($data['auth_provider']);
            $user->setStatus("active");
            $user->save();

            // Send a response
            echo sendResponse(true, 'Successfully created a new account!');
        } else {
            // Send a response
            echo sendResponse(false, $validResponse);
        }
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>