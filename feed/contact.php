<?php
    require_once realpath(dirname(__FILE__) . "/../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../")."/model/Contact.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        header('Content-Type: application/json');

        foreach($data["data"] as $message) {
            $contact = new Contact();

            // Process the data
            $contact->setName($message["name"]);
            $contact->setEmail($message["email"]);
            $contact->setSubject($message["subject"]);
            $contact->setMessage($message["message"]);
            $contact->save();

            // Send a response
            echo sendResponse(true, 'Successfully sent a message!');
        }
    }

    function sendResponse($success, $message) {
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>