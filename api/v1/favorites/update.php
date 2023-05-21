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

        // Process the data
        $user_favorite->setItems(json_encode($data["items"]));
        $user_favorite->save();

        // Send a response
        echo sendResponse(true, 'Successfully update favorites!');
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>