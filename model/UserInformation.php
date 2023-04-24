<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class UserInformation {
        private $id;
        private $firstname;
        private $lastname;
        private $email;
        private $address_line_1;
        private $address_line_2;
        private $city;
        private $contact;
        private $user_id;

        // constructor
        public function __construct($id = null, $firstname = null, $lastname = null, $email = null, $address_line_1 = null, $address_line_2 = null, $city = null, $contact = null, $user_id = null) {
            $this->id = $id;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->email = $email;
            $this->address_line_1 = $address_line_1;
            $this->address_line_2 = $address_line_2;
            $this->city = $city;
            $this->contact = $contact;
            $this->user_id = $user_id;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getFirstname() {
            return $this->firstname;
        }

        public function getLastname() {
            return $this->lastname;
        }

        public function getAddressLine1() {
            return $this->address_line_1;
        }

        public function getAddressLine2() {
            return $this->address_line_2;
        }

        public function getCity() {
            return $this->city;
        }

        public function getContact() {
            return $this->contact;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getUserId() {
            return $this->user_id;
        }

        public function getUserInformation() {
            $userInformation = array(
                "id" => $this->id, 
                "firstname" => $this->firstname, 
                "lastname" => $this->lastname, 
                "email" => $this->email, 
                "address_line_1" => $this->address_line_1, 
                "address_line_2" => $this->address_line_2, 
                "city" => $this->city, 
                "contact" => $this->contact, 
                "user_id" => $this->user_id, 
            );

            return $userInformation;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setFirstname($firstname) {
            $this->firstname = $firstname;
        }

        public function setLastname($lastname) {
            $this->lastname = $lastname;
        }

        public function setEmail($email) {
            $this->email = $email;
        }

        public function setAddressLine1($address_line_1) {
            $this->address_line_1 = $address_line_1;
        }

        public function setAddressLine2($address_line_2) {
            $this->address_line_2 = $address_line_2;
        }

        public function setCity($city) {
            $this->city = $city;
        }

        public function setContact($contact) {
            $this->contact = $contact;
        }

        public function setUserId($user_id) {
            $this->user_id = $user_id;
        }
        
        // save the user information to the database
        public function save() {
            global $mysqli;

            // if the user information has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE user_informations SET firstname=?, lastname=?, email=?, address_line_1=?, address_line_2=?, city=?, contact=?, user_id=? WHERE user_id=?");
                $stmt->bind_param("sssssssii", $this->firstname, $this->lastname, $this->email, $this->address_line_1, $this->address_line_2, $this->city, $this->contact, $this->user_id, $this->user_id);
            }

            // otherwise, insert a new record for the user information
            else {
                $stmt = $mysqli->prepare("INSERT INTO user_informations (firstname, lastname, email, address_line_1, address_line_2, city, contact, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssi", $this->firstname, $this->lastname, $this->email, $this->address_line_1, $this->address_line_2, $this->city, $this->contact, $this->user_id);
            }

            // execute the prepared statement
            $stmt->execute();

            // set the user_information's ID if they were just inserted
            if (!$this->id) {
                $this->id = $mysqli->insert_id;
            }

            // close the statement
            $stmt->close();
        }

        // load a user information from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, firstname, lastname, email, address_line_1, address_line_2, city, contact, user_id FROM user_informations WHERE user_id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $firstname, $lastname, $email, $address_line_1, $address_line_2, $city, $contact, $user_id);

            // if the query returned a result, create and return a UserInformation object
            if ($stmt->fetch()) {
                $userInformation = new UserInformation($id, $firstname, $lastname, $email, $address_line_1, $address_line_2, $city, $contact, $user_id);
                $stmt->close();
                return $userInformation;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        public static function getUsersInformation() {
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

        // delete the user information from the database
        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM user_informations WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>