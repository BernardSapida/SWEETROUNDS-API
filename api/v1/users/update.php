<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/User.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //? Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $user = new User();
        $current_user = $user::loadById($data["id"]);
        $emailFound = $user->loadByEmail($data["email"]);

        if($emailFound !== null) {
            // Send a response
            echo sendResponse(false, 'Email already exist!');
        } else if($current_user) {
            $current_user->setFullname(($data['firstname']) . " " . $data['lastname']);
            $current_user->setEmail($data['email'] ?? $current_user->getEmail());
            $current_user->save();

            // Send a response
            echo sendResponse(true, 'Successfully account updated!');
        } else {
            // Send a response
            echo sendResponse(false, 'Successfully account updated!');
        }
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>