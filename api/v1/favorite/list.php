<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Favorite.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $favorite = new Favorite();

        // Get list of messages
        $favorites = $favorite->getFavorites();

        // Send a response
        echo sendResponse(true, 'Successfully retrieve favorites!', $message);
    }

    function sendResponse($success, $message, $data) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => $data);
        return json_encode($response);
    }
?>