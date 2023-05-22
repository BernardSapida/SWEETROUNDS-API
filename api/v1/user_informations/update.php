<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/UserInformation.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        $user = new UserInformation();
        $user_id = $data['id'];
        $user_information = $user->loadById($user_id);

        // Process the data
        if($user_information) {
            $firstname = $data["firstname"] ?? $user_information->getFirstname();
            $lastname = $data["lastname"] ?? $user_information->getLastname();
            $email = $data["email"] ?? $user_information->getEmail();
            $addressLine1 = $data["address_line_1"] ?? $user_information->getAddressLine1();
            $addressLine2 = $data["address_line_2"] ?? $user_information->getAddressLine2();
            $city = $data["city"] ?? $user_information->getCity();
            $contact = $data["contact"] ?? $user_information->getContact();

            $user_information->setFirstname($firstname);
            $user_information->setLastname($lastname);
            $user_information->setEmail($email);
            $user_information->setAddressLine1($addressLine1);
            $user_information->setAddressLine2($addressLine2);
            $user_information->setCity($city);
            $user_information->setContact($contact);
            $user_information->save();

            // Send a response
            echo sendResponse(true, 'Successfully update account information!');
        } else {
            echo sendResponse(true, 'Invalid account parameters!');
        }

        
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>