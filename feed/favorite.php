<?php
    require_once realpath(dirname(__FILE__) . "/../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../")."/model/Favorite.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        header('Content-Type: application/json');

        foreach($data["data"] as $userFavorite) {
            $favorite = new Favorite();

            // Process the data
            $favorite->setItems(json_encode($userFavorite["items"]));
            $favorite->setUserId($userFavorite["user_id"]);
            $favorite->save();

            // Send a response
            echo sendResponse(true, 'Successfully sent a message!');
        }
    }

    function sendResponse($success, $message) {
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>