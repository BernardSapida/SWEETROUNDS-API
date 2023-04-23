<?php
    require_once dirname(__DIR__)."/utils/database.php";
    require_once dirname(__DIR__)."/helper/hash.php";

    // define a class for your data model
    class Favorite {
        private $id;
        private $items;
        private $user_id;

        // constructor
        public function __construct($id = null, $items = null,  $user_id = null) {
            $this->id = $id;
            $this->items = $items;
            $this->user_id = $user_id;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getItems() {
            return $this->items;
        }

        public function getUserId() {
            return $this->user_id;
        }

        public function getFavorites() {
            $favorites = array(
                "id" => $this->id, 
                "items" => $this->items, 
                "user_id" => $this->user_id, 
            );

            return $favorites;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setItems($items) {
            $this->items = $items;
        }

        public function setUserId($user_id) {
            $this->user_id = $user_id;
        }
        
        // save the favorite to the database
        public function save() {
            global $mysqli;

            // if the favorite has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE favorites SET items=?, user_id=? WHERE id=?");
                $stmt->bind_param("sssii", $this->items, $this->user_id, $this->id);
            }

            // otherwise, insert a new record for the favorite
            else {
                $stmt = $mysqli->prepare("INSERT INTO favorites (items, user_id) VALUES (?, ?)");
                $stmt->bind_param("ss", $this->items, $this->user_id);
            }

            // execute the prepared statement
            $stmt->execute();

            // set the favorites's ID if they were just inserted
            if (!$this->id) {
                $this->id = $mysqli->insert_id;
            }

            // close the statement
            $stmt->close();
        }

        // load a favorite from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, fullname, email, password, auth_provider, status FROM favorites WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $fullname, $email, $password, $auth_provider, $status);

            // if the query returned a result, create and return a Favorite object
            if ($stmt->fetch()) {
                $favorite = new Favorite($id, $fullname, $email, $password, $auth_provider, $status);
                $stmt->close();
                return $favorite;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // delete the favorite from the database
        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM favorites WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>