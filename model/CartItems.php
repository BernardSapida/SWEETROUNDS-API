<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class CartItems {
        private $id;
        private $quantity;
        private $product_id;
        private $user_id;

        // constructor
        public function __construct($id = null, $quantity = null, $product_id = null,  $user_id = null) {
            $this->id = $id;
            $this->quantity = $quantity;
            $this->product_id = $product_id;
            $this->user_id = $user_id;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getQuantity() {
            return $this->quantity;
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
                "quantity" => $this->quantity, 
                "product_id" => $this->product_id, 
                "user_id" => $this->user_id, 
            );

            return $cart;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setQuantity($quantity) {
            $this->quantity = $quantity;
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
                $stmt = $mysqli->prepare("UPDATE cart_items SET quantity=?, product_id=?, user_id=? WHERE id=?");
                $stmt->bind_param("iiii", $this->quantity, $this->product_id, $this->user_id, $this->id);
            }

            // otherwise, insert a new record for the cart
            else {
                $stmt = $mysqli->prepare("INSERT INTO cart_items (quantity, product_id, user_id) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $this->quantity, $this->product_id, $this->user_id);
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

            $stmt = $mysqli->prepare("SELECT id, quantity, product_id, user_id FROM cart_items WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $quantity, $product_id, $user_id);

            // if the query returned a result, create and return a Cart object
            if ($stmt->fetch()) {
                $cart = new CartItems($id, $quantity, $product_id, $user_id);
                $stmt->close();
                return $cart;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        public static function getUserCarts($id) {
            global $mysqli;

            $stmt = $mysqli->prepare(
                "SELECT * FROM (SELECT 
                    P.id AS 'product_id', 
                    P.product_number, 
                    P.name, 
                    P.flavor, 
                    P.price, 
                    P.quantity as 'product_quantity', 
                    C.quantity as 'cart_quantity', 
                    P.image, 
                    P.availability, 
                    F.id AS 'favorite_id', 
                    C.id AS 'cart_id',
                CASE
                    WHEN F.product_id IS NULL THEN FALSE
                    ELSE TRUE
                END AS 'in_favorite',
                CASE
                    WHEN C.product_id IS NULL THEN FALSE
                    ELSE TRUE
                END AS 'in_cart'
                FROM products AS P
                LEFT JOIN (SELECT id, product_id FROM favorites WHERE user_id = ?) AS F ON P.id = F.product_id
                LEFT JOIN (SELECT id, product_id, quantity FROM cart_items WHERE cart_items.user_id = ?) AS C ON P.id = C.product_id) AS A
                WHERE A.in_cart = 1;"
            );
            $stmt->bind_param("ii", $id, $id);
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