<?php
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/validation/create_user.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/User.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Favorite.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/UserInformation.php";
    require_once realpath(dirname(__FILE__) . "/../../../")."/model/Cart.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);
        
        $validResponse = valid($data);
        $user = new User();
        $email = $user->loadByEmail($data["email"]);

        // Process the data
        if($email !== null) {
            // Send a response
            echo sendResponse(false, 'Email already exist!');
        } else if($validResponse === true) {
            $user->setFullname($data['firstname'] . " " . $data['lastname']);
            $user->setEmail($data['email']);
            $user->setPassword($data['password']);
            $user->setAuthProvider($data['auth_provider']);
            $user->setStatus("inactive");
            $user->save();

            createFavorite($user);
            createUserInformation($data['firstname'], $data['lastname'], $user);
            createCart($user);

            // Send a response
            echo sendResponse(true, 'Successfully created a new account!');
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

    function createFavorite($user) {
        $favorite = new Favorite();
        $favorite->setItems(json_encode(array()));
        $favorite->setUserId($user->getId());
        $favorite->save();
    }

    function createUserInformation($firstname, $lastname, $user) {
        $UI = new UserInformation();
        $UI->setFirstname($firstname);
        $UI->setLastname($lastname);
        $UI->setEmail($user->getEmail());
        $UI->setUserId($user->getId());
        $UI->save();
    }

    function createCart($user) {
        $cart = new Cart();
        $cart->setItems(json_encode(array()));
        $cart->setUserId($user->getId());
        $cart->save();
    }
?>