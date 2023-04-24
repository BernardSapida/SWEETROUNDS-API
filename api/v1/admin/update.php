<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Admin.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        $admin = new Admin();
        $current_admin = $admin::loadById($data["id"]);
        $emailFound = $admin->loadByEmail($data["email"]);

        if($emailFound !== null) {
            // Send a response
            echo sendResponse(false, 'Email already exist!');
        } else if($current_admin) {
            $employee_firstname = $data["employee_firstname"] == "" ? $current_admin->getEmployeeFirstname() : $data["employee_firstname"];
            $employee_lastname = $data["employee_lastname"] == "" ? $current_admin->getEmployeeLastname() : $data["employee_lastname"];
            $email = $data["email"] == "" ? $current_admin->getEmail() : $data["email"];
            $role = $data["role"] == "" ? $current_admin->getRole() : $data["role"];

            $current_admin->setEmployeeFirstname($employee_firstname);
            $current_admin->setEmployeeLastname($employee_lastname);
            $current_admin->setEmail($email);
            $current_admin->setRole($role);
            $current_admin->save();

            // Send a response
            echo sendResponse(true, 'Successfully admin account updated!');
        } else {
            // Send a response
            echo sendResponse(false, 'Invalid parameters!');
        }
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>