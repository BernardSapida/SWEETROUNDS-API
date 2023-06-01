<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Setting.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //! Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        $setting = new Setting();
        $admin_setting = $setting->loadById(1);

        // Process the data
        $admin_setting->setTax($data["tax"]);
        $admin_setting->setDiscount($data["discount"]);
        $admin_setting->setAcceptingOrder($data["accepting_order"]);
        $admin_setting->save();

        // Send a response
        echo sendResponse(true, 'Successfully update setting!');
    }

    function sendResponse($success, $message) {
        header('Content-Type: application/json');
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>