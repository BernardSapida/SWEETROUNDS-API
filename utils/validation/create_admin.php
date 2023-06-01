<?php
  // ***
  function valid($data) {
      $employee_firstname = $data['employee_firstname'];
      $employee_lastname = $data['employee_lastname'];
      $email = $data['email'];
      $password = $data['password'];
      // $confirmPassword = $data['confirmPassword'];
      $role = $data['role'];

      if(!validName($employee_firstname)) return "Firstname must contain only letters and have at least 2 letters";
      if(!validName($employee_lastname)) return "Lastname must contain only letters and have at least 2 letters";
      else if(!validEmail($email)) return "Invalid email address";
      else if(!validPassword($password)) return "Invalid password";
      // else if(!validPasswordConfirmation($password, $confirmPassword)) return "Passwords did not match";
      else if(!validRole($role)) return "Invalid role";

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

  // ***
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
  
  function validRole($role) {
    $roles = array('Manager', 'Order Fulfillment Specialist', 'Cashier');
    return in_array($role, $roles);
  }

  function validsStatus($status) {
    $statuses = array('active', 'inactive');
    return in_array($status, $statuses);
  }
?>