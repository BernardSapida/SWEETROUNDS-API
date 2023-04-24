<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class Cart {
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

        public function getCartDetails() {
            $cart = array(
                "id" => $this->id, 
                "items" => $this->items, 
                "user_id" => $this->user_id, 
            );

            return $cart;
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
        
        // save the cart to the database
        public function save() {
            global $mysqli;

            // if the cart has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE cart_items SET items=?, user_id=? WHERE id=?");
                $stmt->bind_param("sii", $this->items, $this->user_id, $this->id);
            }

            // otherwise, insert a new record for the cart
            else {
                $stmt = $mysqli->prepare("INSERT INTO cart_items (items, user_id) VALUES (?, ?)");
                $stmt->bind_param("si", $this->items, $this->user_id);
            }

            // execute the prepared statement
            $stmt->execute();

            // set the cart's ID if they were just inserted
            if (!$this->id) {
                $this->id = $mysqli->insert_id;
            }

            // close the statement
            $stmt->close();
        }

        // load a cart from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, items, user_id FROM cart_items WHERE user_id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $items, $user_id);

            // if the query returned a result, create and return a Cart object
            if ($stmt->fetch()) {
                $cart = new Cart($id, $items, $user_id);
                $stmt->close();
                return $cart;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // delete the cart from the database
        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM cart_items WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>