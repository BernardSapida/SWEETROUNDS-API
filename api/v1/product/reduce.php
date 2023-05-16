<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Product.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        $product_id = $data['id'];

        $product = new Product();
        $current_product = $product->loadById($product_id);

        if($current_product) {
            // Process the data
            $current_product->setQuantity($current_product->getQuantity() - $data["quantity"]);
            $current_product->setQuantitySold($data["quantity"]);

            if($current_product->getQuantity() == 0) {
                $current_product->setAvailability("Not Available");
            }

            $current_product->save();

            // Send a response
            echo sendResponse(true, 'Successfully updated a product!');
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