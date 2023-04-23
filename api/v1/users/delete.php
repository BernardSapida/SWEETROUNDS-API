<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/User.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $user = new User();
        $current_user = $user::loadById($data["id"]);

        // Delete user account
        $current_user->delete();

        // Send a response
        header('Content-Type: application/json');
        $response = array('success' => true, 'message' => 'Successfully deleted an account!');
        echo json_encode($response);
    }
?>