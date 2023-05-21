<?php
    require_once realpath(dirname(__FILE__) . "/../")."/utils/validation/create_admin.php";
    require_once realpath(dirname(__FILE__) . "/../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../")."/model/Admin.php";
    require_once realpath(dirname(__FILE__) . "/../")."/model/Favorite.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        
        header('Content-Type: application/json');
        
        foreach($data["data"] as $user) {
            $validResponse = valid($user);
            $admin = new Admin();
            $email = $admin->loadByEmail($user["email"]);

            // Process the user
            if($email !== null) {
                // Send a response
                echo sendResponse(false, 'Email already exist!');
            } else if($validResponse === true) {
                $admin->setEmployeeFirstname($user['employee_firstname']);
                $admin->setEmployeeLastname($user['employee_lastname']);
                $admin->setEmail($user['email']);
                $admin->setPassword($user['password']);
                $admin->setRole($user['role']);
                $admin->setStatus("Offline");
                $admin->save();

                // Send a response
                echo sendResponse(true, 'Successfully created a new admin account!');
            } else {
                // Send a response
                echo sendResponse(false, $validResponse);
            }
        }
    }

    function sendResponse($success, $message) {
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>