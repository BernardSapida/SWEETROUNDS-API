<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class OrderDetail {
        private $id;
        private $firstname;
        private $lastname;
        private $address_line_1;
        private $address_line_2;
        private $city;
        private $contact;
        private $order_id;

        // constructor
        public function __construct($id = null, $firstname = null, $lastname = null, $address_line_1 = null, $address_line_2 = null, $city = null, $contact = null, $order_id = null) {
            $this->id = $id;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->address_line_1 = $address_line_1;
            $this->address_line_2 = $address_line_2;
            $this->city = $city;
            $this->contact = $contact;
            $this->order_id = $order_id;
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

        public function getOrderId() {
            return $this->order_id;
        }

        public function getOrderDetails() {
            $orderDetails = array(
                "id" => $this->id, 
                "firstname" => $this->firstname, 
                "lastname" => $this->lastname, 
                "address_line_1" => $this->address_line_1, 
                "address_line_2" => $this->address_line_2, 
                "city" => $this->city, 
                "contact" => $this->contact, 
                "order_id" => $this->order_id, 
            );

            return $orderDetails;
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

        public function setOrderId($order_id) {
            $this->order_id = $order_id;
        }
        
        // save the order detail to the database
        public function save() {
            global $mysqli;

            // if the order detail has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE order_details SET firstname=?, lastname=?, address_line_1=?, address_line_2=?, city=?, contact=?, order_id=? WHERE id=?");
                $stmt->bind_param("ssssssii", $this->firstname, $this->lastname, $this->address_line_1, $this->address_line_2, $this->city, $this->contact, $this->order_id, $this->id);
            }

            // otherwise, insert a new record for the order detail
            else {
                $stmt = $mysqli->prepare("INSERT INTO order_details (firstname, lastname, address_line_1, address_line_2, city, contact, order_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssi", $this->firstname, $this->lastname, $this->address_line_1, $this->address_line_2, $this->city, $this->contact, $this->order_id);
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

        // load a order detail from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, firstname, lastname, address_line_1, address_line_2, city, contact, order_id FROM order_details WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $firstname, $lastname, $address_line_1, $address_line_2, $city, $contact, $order_id);

            // if the query returned a result, create and return a OrderDetail object
            if ($stmt->fetch()) {
                $orderInformation = new OrderDetail($id, $firstname, $lastname, $address_line_1, $address_line_2, $city, $contact, $order_id);
                $stmt->close();
                return $orderInformation;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        public static function getUsersInformation() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM users LEFT JOIN order_details ON users.id = order_details.user_id");
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

        // delete the order detail from the database
        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM order_details WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>