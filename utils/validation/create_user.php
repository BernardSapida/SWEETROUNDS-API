<?php
    function valid($data) {
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $email = $data['email'];
        $password = $data['password'];
        $confirmPassword = $data['confirmPassword'];
        $foundAuth = foundAuth($data);
        $auth = $foundAuth ? $data["auth_provider"] : null;

        if(!$foundAuth) return "Missing authentication";
        else if(validAuthProvider($auth) != 1) return "Invalid authentication provider";
        else if(!validName($firstname)) return "Firstname must contain only letters and have at least 2 letters";
        else if(!validName($lastname)) return "Lastname must contain only letters and have at least 2 letters";
        else if(!validEmail($email)) return "Invalid email address";
        else if(!validPassword($password)) return "Invalid password";
        else if(!validPasswordConfirmation($password, $confirmPassword)) return "Passwords did not match";

        return true;
    }

    function validName($name) {
        // Check if the first name contains only letters and is at least 2 characters long
        return ctype_alpha($name) && strlen($name) >= 2;
    }
      
    function validEmail($email) {
        // Regular expression pattern for email validation
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
      
        // Check if the email matches the pattern
        if (preg_match($pattern, $email)) {
          return true;// Email is valid
        } else {
          return false; // Email is invalid
        }
    }

    function validPassword($password) {
        // Define the regular expression pattern for password validation
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/';
      
        // Check if the password matches the pattern
        if (preg_match($pattern, $password)) {
          return true; // Password is valid
        } else {
          return false; // Password is invalid
        }
    }

    function validPasswordConfirmation($password, $confirmPassword) {
        return $password === $confirmPassword;
    }

    function foundAuth($data) {
        return array_key_exists("auth_provider", $data);
    }

    function validAuthProvider($auth) {
        $providers = array('Credentials', 'Google', 'Facebook', 'Github');
        
        return in_array($auth, $providers);
    }
?>