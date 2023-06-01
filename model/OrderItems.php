<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class OrderItems {
        // !
        private $id;
        private $quantity;
        private $order_id;
        private $product_id;

        // constructor
        public function __construct($id = null, $quantity = null, $order_id = null,  $product_id = null) {
            $this->id = $id;
            $this->quantity = $quantity;
            $this->order_id = $order_id;
            $this->product_id = $product_id;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getQuantity() {
            return $this->quantity;
        }

        public function getOrderId() {
            return $this->order_id;
        }

        public function getProductId() {
            return $this->product_id;
        }

        public function getOrderItemsDetails() {
            $order_items = array(
                "id" => $this->id, 
                "quantity" => $this->quantity, 
                "order_id" => $this->order_id, 
                "product_id" => $this->product_id, 
            );

            return $order_items;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setQuantity($quantity) {
            $this->quantity = $quantity;
        }

        public function setOrderId($order_id) {
            $this->order_id = $order_id;
        }

        public function setProductId($product_id) {
            $this->product_id = $product_id;
        }
        
        // save the order_items to the database
        public function save() {
            global $mysqli;

            // if the order_items has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE order_items SET quantity=?, order_id=?, product_id=? WHERE id=?");
                $stmt->bind_param("iiii", $this->quantity, $this->order_id, $this->product_id, $this->id);
            }

            // otherwise, insert a new record for the order_items
            else {
                $stmt = $mysqli->prepare("INSERT INTO order_items (quantity, order_id, product_id) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $this->quantity, $this->order_id, $this->product_id);
            }

            // execute the prepared statement
            $stmt->execute();

            // set the order_items's ID if they were just inserted
            if (!$this->id) {
                $this->id = $mysqli->insert_id;
            }

            // close the statement
            $stmt->close();
        }

        // load a order_items from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, quantity, order_id, product_id FROM order_items WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $quantity, $order_id, $product_id);

            // if the query returned a result, create and return a Order object
            if ($stmt->fetch()) {
                $order_items = new OrderItems($id, $quantity, $order_id, $product_id);
                $stmt->close();
                return $order_items;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // delete the order_items from the database
        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM order_items WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>