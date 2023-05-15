<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Setting.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        
        $setting = new Setting();
        $admin_setting = $setting->loadById(1);

        // Get setting details
        $setting_details = $admin_setting->getSettingDetails();

        // Send a response
        echo sendResponse(true, 'Successfully retrieve setting!', $setting_details);
    }

    function sendResponse($success, $message, $data) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message, 'data' => $data);
        return json_encode($response);
    }
?>