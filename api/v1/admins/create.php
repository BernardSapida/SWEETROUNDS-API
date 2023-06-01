<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/validation/create_admin.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Admin.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Favorite.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //! Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        
        $validResponse = valid($data);
        $admin = new Admin();
        $email = $admin->loadByEmail($data["email"]);

        // Process the data
        if($email !== null) {
            // Send a response
            echo sendResponse(false, 'Email already exist!');
        } else if($validResponse === true) {
            $admin->setEmployeeFirstname($data['employee_firstname']);
            $admin->setEmployeeLastname($data['employee_lastname']);
            $admin->setEmail($data['email']);
            $admin->setPassword($data['password']);
            $admin->setRole($data['role']);
            $admin->setAccountStatus("Active");
            $admin->setOnlineStatus("Offline");
            $admin->save();

            // createFavorite($user);

            // Send a response
            echo sendResponse(true, 'Successfully created a new admin account!');
        } else {
            // Send a response
            echo sendResponse(false, $validResponse);
        }
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>