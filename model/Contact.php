<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class Contact {
        private $id;
        private $name;
        private $email;
        private $subject;
        private $message;

        // constructor
        public function __construct($id = null, $name = null,  $email = null,  $subject = null,  $message = null) {
            $this->id = $id;
            $this->name = $name;
            $this->email = $email;
            $this->subject = $subject;
            $this->message = $message;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getName() {
            return $this->name;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getSubject() {
            return $this->subject;
        }

        public function getMessage() {
            return $this->message;
        }

        public function getMessageDetails() {
            $contact_message = array(
                "id" => $this->id, 
                "name" => $this->name, 
                "email" => $this->email, 
                "subject" => $this->subject, 
                "message" => $this->message, 
            );

            return $contact_message;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setName($name) {
            $this->name = $name;
        }

        public function setEmail($email) {
            $this->email = $email;
        }

        public function setSubject($subject) {
            $this->subject = $subject;
        }

        public function setMessage($message) {
            $this->message = $message;
        }
        
        // save the contact message to the database
        public function save() {
            global $mysqli;

            // if the contact message has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE contact_messages SET name=?, email=?, subject=?, message=? WHERE id=?");
                $stmt->bind_param("ssssi", $this->name, $this->email, $this->subject, $this->message, $this->id);
            }

            // otherwise, insert a new record for the contact message
            else {
                $stmt = $mysqli->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $this->name, $this->email, $this->subject, $this->message);
            }

            // execute the prepared statement
            $stmt->execute();

            // set the contact message's ID if they were just inserted
            if (!$this->id) {
                $this->id = $mysqli->insert_id;
            }

            // close the statement
            $stmt->close();
        }

        // load a contact message from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, name, flavor, type, price FROM contact_messages WHERE user_id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $name, $flavor, $type, $price);

            // if the query returned a result, create and return a Contact object
            if ($stmt->fetch()) {
                $contact_message = new Contact($id, $name, $flavor, $type, $price);
                $stmt->close();
                return $contact_message;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        public static function getMessages() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM contact_messages");
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

        // delete the contact message from the database
        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM contact_messages WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>