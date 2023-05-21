<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class Favorite {
        private $id;
        private $product_id;
        private $user_id;

        // constructor
        public function __construct($id = null, $product_id = null,  $user_id = null) {
            $this->id = $id;
            $this->product_id = $product_id;
            $this->user_id = $user_id;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getProductId() {
            return $this->product_id;
        }

        public function getUserId() {
            return $this->user_id;
        }

        public function getFavoritesDetail() {
            $favorites = array(
                "id" => $this->id, 
                "product_id" => $this->product_id, 
                "user_id" => $this->user_id, 
            );

            return $favorites;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setProductId($product_id) {
            $this->product_id = $product_id;
        }

        public function setUserId($user_id) {
            $this->user_id = $user_id;
        }
        
        // save the favorite to the database
        public function save() {
            global $mysqli;

            // if the favorite has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE favorites SET product_id=?, user_id=? WHERE id=?");
                $stmt->bind_param("sii", $this->product_id, $this->user_id, $this->id);
            }

            // otherwise, insert a new record for the favorite
            else {
                $stmt = $mysqli->prepare("INSERT INTO favorites (product_id, user_id) VALUES (?, ?)");
                $stmt->bind_param("si", $this->product_id, $this->user_id);
            }

            // execute the prepared statement
            $stmt->execute();

            // set the favorite's ID if they were just inserted
            if (!$this->id) {
                $this->id = $mysqli->insert_id;
            }

            // close the statement
            $stmt->close();
        }

        // load a favorite from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, product_id, user_id FROM favorites WHERE user_id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $product_id, $user_id);

            // if the query returned a result, create and return a Favorite object
            if ($stmt->fetch()) {
                $favorite = new Favorite($id, $product_id, $user_id);
                $stmt->close();
                return $favorite;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        public static function getFavorites() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM favorites");
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