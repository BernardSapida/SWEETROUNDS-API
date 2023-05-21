<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Favorite.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        $favorite = new Favorite();
        $user_id = $data['id'];
        $user_favorite = $favorite->loadById($user_id);

        // Get list of messages
        $favorites = $user_favorite->getItems();

        // Send a response
        echo sendResponse(true, 'Successfully retrieve favorites!', $favorites);
    }

    function sendResponse($success, $message, $data) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => json_decode($data));
        return json_encode($response);
    }
?>