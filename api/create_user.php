<?php
    require "../util/validation/create_user.php";
    require "../util/database.php";
    require "../helper/hash.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        // Process the data
        if(valid($data)) {
            $fullname = $data['firstname'] . " " . $data['lastname'];
            $email = $data['email'];
            $hashedPassword = hashPassword($data['password']);
            $auth_provider = $data['auth_provider'];
            $status = "active";

            // Save it to a database
            $sql = "INSERT INTO `users` (fullname, email, password, auth_provider, status) VALUES ('$fullname', '$email', '$hashedPassword', '$auth_provider', '$status')";
            database_query($sql);
        }


        // Send a response
        header('Content-Type: application/json');

        $response = array('message' => 'Successfully created a new account!');

        echo json_encode($response);
    } else {
    // Handle other HTTP methods here
    }
?>
