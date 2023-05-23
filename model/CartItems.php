<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class CartItems {
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

        public function getCartDetails() {
            $cart = array(
                "id" => $this->id, 
                "product_id" => $this->product_id, 
                "user_id" => $this->user_id, 
            );

            return $cart;
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
        
        // save the cart to the database
        public function save() {
            global $mysqli;

            // if the cart has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE cart_items SET product_id=?, user_id=? WHERE id=?");
                $stmt->bind_param("sii", $this->product_id, $this->user_id, $this->id);
            }

            // otherwise, insert a new record for the cart
            else {
                $stmt = $mysqli->prepare("INSERT INTO cart_items (product_id, user_id) VALUES (?, ?)");
                $stmt->bind_param("si", $this->product_id, $this->user_id);
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

            $stmt = $mysqli->prepare("SELECT id, product_id, user_id FROM cart_items WHERE user_id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $product_id, $user_id);

            // if the query returned a result, create and return a Cart object
            if ($stmt->fetch()) {
                $cart = new CartItems($id, $product_id, $user_id);
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
        public function deleteCartDonut($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM cart_items WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
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