<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/User.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $user = new User();
        $current_user = $user::loadById($data["id"]);

        if($current_user) {
            $status = $data['status'] == "" ? $current_user->getStatus() : $data['status'];

            $current_user->setStatus($status);
            $current_user->save();
    
            // Send a response
            echo sendResponse(true, 'Successfully status updated!');
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