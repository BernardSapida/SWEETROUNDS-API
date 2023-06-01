<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Contact.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // !
        $contact = new Contact();
        
        // Get list of messages
        $message = $contact->getMessages();

        // Send a response
        echo sendResponse(true, 'Successfully retrieve messages!', $message);
    }

    function sendResponse($success, $message, $data) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => $data);
        return json_encode($response);
    }
?>