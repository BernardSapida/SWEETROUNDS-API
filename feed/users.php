<?php
    require_once realpath(dirname(__FILE__) . "/../")."/utils/validation/create_user.php";
    require_once realpath(dirname(__FILE__) . "/../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../")."/model/User.php";
    require_once realpath(dirname(__FILE__) . "/../")."/model/Favorite.php";
    require_once realpath(dirname(__FILE__) . "/../")."/model/UserInformation.php";
    require_once realpath(dirname(__FILE__) . "/../")."/model/Cart.php";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $postData = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($postData, true);

        header('Content-Type: application/json');

        foreach($data["data"] as $curren_user) {
            $validResponse = valid($curren_user);
            $user = new User();
            $email = $user->loadByEmail($curren_user["email"]);
    
            // Process the data
            if($email !== null) {
                // Send a response
                echo sendResponse(false, 'Email already exist!');
            } else if($validResponse === true) {
                $user->setFullname($curren_user['firstname'] . " " . $curren_user['lastname']);
                $user->setEmail($curren_user['email']);
                $user->setPassword($curren_user['password']);
                $user->setAuthProvider($curren_user['auth_provider']);
                $user->setStatus("inactive");
                $user->save();
    
                createFavorite($user);
                createUserInformation($curren_user['firstname'], $curren_user['lastname'], $user);
                createCart($user);
    
                // Send a response
                echo sendResponse(true, 'Successfully created a new account!');
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