<?php
    require_once realpath(dirname(__FILE__) . "/../")."/utils/database.php";
    require_once realpath(dirname(__FILE__) . "/../")."/helpers/hash.php";

    class User {
        private $id;
        private $fullname;
        private $email;
        private $password;
        private $auth_provider;
        private $account_status;
        private $online_status;

        // constructor
        public function __construct($id = null, $fullname = null,  $email = null, $password = null, $auth_provider = null, $account_status = null, $online_status = null) {
            $this->id = $id;
            $this->fullname = $fullname;
            $this->email = $email;
            $this->password = $password;
            $this->auth_provider = $auth_provider;
            $this->account_status = $account_status;
            $this->online_status = $online_status;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getFullname() {
            return $this->fullname;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getPassword() {
            return $this->password;
        }

        public function getAuthProvider() {
            return $this->auth_provider;
        }

        public function getAccountStatus() {
            return $this->account_status;
        }

        public function getOnlineStatus() {
            return $this->online_status;
        }

        public function getUserDetails() {
            $userDetails = array(
                "id" => $this->id, 
                "fullname" => $this->fullname, 
                "email" => $this->email, 
                "password" => $this->password, 
                "auth_provider" => $this->auth_provider, 
                "account_status" => $this->account_status, 
                "online_status" => $this->online_status, 
            );

            return $userDetails;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setFullname($fullname) {
            $this->fullname = $fullname;
        }

        public function setEmail($email) {
            $this->email = $email;
        }
        
        public function setPassword($password) {
            $hashedPassword = hashPassword($password);
            $this->password = $hashedPassword;
        }

        public function setAuthProvider($auth_provider) {
            $this->auth_provider = $auth_provider;
        }

        public function setAccountStatus($account_status) {
            $this->account_status = $account_status;
        }

        public function setOnlineStatus($online_status) {
            $this->online_status = $online_status;
        }

        // save the user to the database
        public function save() {
            global $mysqli;

            // if the user has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE users SET fullname=?, email=?, password=?, auth_provider=?, account_status=?, online_status=? WHERE id=?");
                $stmt->bind_param("ssssssi", $this->fullname, $this->email, $this->password, $this->auth_provider, $this->account_status, $this->online_status, $this->id);
            }

            // otherwise, insert a new record for the user
            else {
                $stmt = $mysqli->prepare("INSERT INTO users (fullname, email, password, auth_provider, account_status, online_status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $this->fullname, $this->email, $this->password, $this->auth_provider, $this->account_status, $this->online_status);
            }

            // execute the prepared statement
            $stmt->execute();

            // set the user's ID if they were just inserted
            if (!$this->id) {
                $this->id = $mysqli->insert_id;
            }

            // close the statement
            $stmt->close();
        }

        // load a user from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, fullname, email, password, auth_provider, account_status, online_status FROM users WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $fullname, $email, $password, $auth_provider, $account_status, $online_status);

            // if the query returned a result, create and return a User object
            if ($stmt->fetch()) {
                $user = new User($id, $fullname, $email, $password, $auth_provider, $account_status, $online_status);
                $stmt->close();
                return $user;
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

            $stmt = $mysqli->prepare(
                "SELECT id, fullname, email, password, auth_provider, account_status, online_status 
                FROM users 
                WHERE email=?"
            );
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($id, $fullname, $email, $password, $auth_provider, $account_status, $online_status);

            // if the query returned a result, create and return a User object
            if ($stmt->fetch()) {
                $user = new User($id, $fullname, $email, $password, $auth_provider, $account_status, $online_status);
                $stmt->close();
                return $user;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // search users 
        public static function searchUser($keyword) {
            global $mysqli;

            $stmt = $mysqli->prepare(
                "SELECT * 
                FROM users 
                LEFT JOIN user_informations ON users.id = user_informations.user_id
                WHERE CONCAT_WS(' ', users.fullname, users.email, users.password, users.auth_provider, user_informations.address_line_1, user_informations.address_line_2, user_informations.city, user_informations.contact, users.account_status, users.online_status, users.created_at) 
                LIKE '%$keyword%';"
            );
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = array();

            // Add each record in result to rows
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }

        // get user list
        public static function getUsers() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM users LEFT JOIN user_informations ON users.id = user_informations.user_id");
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

        // get list of new users of the month
        public static function getNewUsers() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM users WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE());");
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

        // delete the user from the database
        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM users WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>