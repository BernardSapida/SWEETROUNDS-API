<?php
    require_once dirname(__DIR__)."/utils/database.php";
    require_once dirname(__DIR__)."/helper/hash.php";

    class Admin {
        private $id;
        private $employee_id;
        private $employee_name;
        private $username;
        private $password;
        private $role;
        private $status;

        // constructor
        public function __construct($id = null, $employee_id = null, $employee_name = null,  $username = null, $password = null, $role = null, $status = null) {
            $this->id = $id;
            $this->employee_id = $employee_id;
            $this->employee_name = $employee_name;
            $this->username = $username;
            $this->password = $password;
            $this->role = $role;
            $this->status = $status;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getEmployeeId() {
            return $this->employee_id;
        }

        public function getEmployeeName() {
            return $this->employee_name;
        }

        public function getUsername() {
            return $this->username;
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
                "employee_id" => $this->employee_id, 
                "username" => $this->username, 
                "password" => $this->password, 
                "role" => $this->role, 
                "status" => $this->status, 
            );

            return $adminDetails;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setEmployeeId($employee_id) {
            $this->employee_id = $employee_id;
        }

        public function setEmployeeName($employee_name) {
            $this->employee_name = $employee_name;
        }

        public function setEmployeeUsername($username) {
            $this->username = $username;
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
                $stmt = $mysqli->prepare("UPDATE admins SET employee_id=?, employee_name=?, username=?, password=?, role=?, status->? WHERE id=?");
                $stmt->bind_param("isssssi", $this->employee_id, $this->employee_name, $this->username, $this->password, $this->role, $this->status, $this->id);
            }

            // otherwise, insert a new record for the admin
            else {
                $stmt = $mysqli->prepare("INSERT INTO admins (employee_id, employee_name, username, password, role, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssss", $this->employee_id, $this->employee_name, $this->username, $this->password, $this->role, $this->status);
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

            $stmt = $mysqli->prepare("SELECT id, employee_id, employee_name, username, password, role, status FROM admins WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($employee_id, $employee_name, $username, $password, $role, $status);

            // if the query returned a result, create and return a User object
            if ($stmt->fetch()) {
                $admin = new User($id, $employee_id, $employee_name, $username, $password, $role, $status);
                $stmt->close();
                return $admin;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
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