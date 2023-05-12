<?php
    require_once realpath(dirname(__FILE__) . "/../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../")."/model/Product.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
       
        header('Content-Type: application/json');

        foreach($data["data"] as $donut) {
            $product = new Product();

            // Process the data
            $product->setProductNumber($donut["product_number"]);
            $product->setName($donut["name"]);
            $product->setFlavor($donut["flavor"]);
            $product->setPrice($donut["price"]);
            $product->setQuantity($donut["quantity"]);
            $product->setQuantitySold($donut["quantity_sold"]);
            $product->setAvailability($donut["availability"]);
            $product->save();

            // Send a response
            echo sendResponse(true, 'Successfully created a new product!');
        }
    }

    function sendResponse($success, $message) {
        $response = array('success' => $success, 'message' => $message);
        return json_encode($response);
    }
?>