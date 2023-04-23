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
        $current_user = $user::loadById($data["id"]);

        $current_user->setFullname($data['firstname'] . " " . $data['lastname']);
        $current_user->setEmail($data['email']);
        $user->save();

        // Send a response
        header('Content-Type: application/json');
        $response = array('success' => true, 'message' => 'Successfully account updated!');
        echo json_encode($response);
    }
?>