<?php
    require_once dirname(__DIR__)."/utils/database.php";
    require_once dirname(__DIR__)."/helpers/hash.php";

    class Admin {
        private $id;
        private $employee_firstname;
        private $employee_lastname;
        private $email;
        private $password;
        private $role;
        private $status;

        // constructor
        public function __construct($id = null, $employee_firstname = null, $employee_lastname = null,  $email = null, $password = null, $role = null, $status = null) {
            $this->id = $id;
            $this->employee_firstname = $employee_firstname;
            $this->employee_lastname = $employee_lastname;
            $this->email = $email;
            $this->password = $password;
            $this->role = $role;
            $this->status = $status;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getEmployeeFirstname() {
            return $this->employee_firstname;
        }

        public function getEmployeeLastname() {
            return $this->employee_lastname;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getPassword() {
            return $this->password;
        }

        public function getRole() {
            return $this->role;
        }

        public function getStatus() {
            return $this->status;
        }

        public function getAdminDetails() {
            $adminDetails = array(
                "id" => $this->id, 
                "employee_firstname" => $this->employee_firstname, 
                "employee_lastname" => $this->employee_lastname, 
                "email" => $this->email, 
                "password" => $this->password, 
                "role" => $this->role, 
                "status" => $this->status, 
            );

            return $adminDetails;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setEmployeeFirstname($employee_firstname) {
            $this->employee_firstname = $employee_firstname;
        }

        public function setEmployeeLastname($employee_lastname) {
            $this->employee_lastname = $employee_lastname;
        }

        public function setEmail($email) {
            $this->email = $email;
        }
        
        public function setPassword($password) {
            $hashedPassword = hashPassword($password);
            $this->password = $hashedPassword;
        }

        public function setRole($role) {
            $this->role = $role;
        }

        public function setStatus($status) {
            $this->status = $status;
        }

        // save the admin to the database
        public function save() {
            global $mysqli;

            // if the admin has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE admins SET employee_firstname=?, employee_lastname=?, email=?, password=?, role=?, status=? WHERE id=?");
                $stmt->bind_param("ssssssi", $this->employee_firstname, $this->employee_lastname, $this->email, $this->password, $this->role, $this->status, $this->id);
            }

            // otherwise, insert a new record for the admin
            else {
                $stmt = $mysqli->prepare("INSERT INTO admins (employee_firstname, employee_lastname, email, password, role, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $this->employee_firstname, $this->employee_lastname, $this->email, $this->password, $this->role, $this->status);
            }

            // execute the prepared statement
            $stmt->execute();

            // set the admin's ID if they were just inserted
            if (!$this->id) {
                $this->id = $mysqli->insert_id;
            }

            // close the statement
            $stmt->close();
        }

        // load a admin from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, employee_firstname, employee_lastname, email, password, role, status FROM admins WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $employee_firstname, $employee_lastname, $email, $password, $role, $status);

            // if the query returned a result, create and return a Admin object
            if ($stmt->fetch()) {
                $admin = new Admin($id, $employee_firstname, $employee_lastname, $email, $password, $role, $status);
                $stmt->close();
                return $admin;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // load a user from the database by email
        public static function loadByEmail($email) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, employee_firstname, employee_lastname, email, password, role, status FROM admins WHERE email=?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($id, $employee_firstname, $employee_lastname, $email, $password, $role, $status);

            // if the query returned a result, create and return a Admin object
            if ($stmt->fetch()) {
                $user = new Admin($id, $employee_firstname, $employee_lastname, $email, $password, $role, $status);
                $stmt->close();
                return $user;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // get admin list
        public static function getAdmins() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM admins");
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $rows = array();

            // Add each record in result to rows
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }

        // delete the admin from the database
        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM admins WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>