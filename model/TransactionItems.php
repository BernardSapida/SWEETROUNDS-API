<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class TransactionItems {
        private $id;
        private $quantity;
        private $transaction_id;
        private $product_id;

        // constructor
        public function __construct($id = null, $quantity = null, $transaction_id = null, $product_id = null) {
            $this->id = $id;
            $this->quantity = $quantity;
            $this->transaction_id = $transaction_id;
            $this->product_id = $product_id;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getQuantity() {
            return $this->quantity;
        }

        public function getTransactionId() {
            return $this->transaction_id;
        }

        public function getProductId() {
            return $this->product_id;
        }

        public function getTransaction() {
            $transaction_items = array(
                "id" => $this->id, 
                "quantity" => $this->quantity, 
                "transaction_id" => $this->transaction_id, 
                "product_id" => $this->product_id, 
            );

            return $transaction_items;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setQuantity($quantity) {
            $this->quantity = $quantity;
        }

        public function setTransactionId($transaction_id) {
            $this->transaction_id = $transaction_id;
        }

        public function setProductId($product_id) {
            $this->product_id = $product_id;
        }
        
        // save the transaction items to the database
        public function save() {
            global $mysqli;

            // if the transaction items has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE transaction_items SET quantity, transaction_id=?, product_id=? WHERE id=?");
                $stmt->bind_param("iiii", $this->quantity, $this->transaction_id, $this->product_id, $this->id);
            }

            // otherwise, insert a new record for the transaction items
            else {
                $stmt = $mysqli->prepare("INSERT INTO transaction_items (quantity, transaction_id, product_id) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $this->quantity, $this->transaction_id, $this->product_id);
            }

            // execute the prepared statement
            $stmt->execute();

            // set the transaction items's ID if they were just inserted
            if (!$this->id) {
                $this->id = $mysqli->insert_id;
            }

            // close the statement
            $stmt->close();
        }

        // load a transaction items from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, quantity, transaction_id, product_id FROM transaction_items WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $quantity, $transaction_id, $product_id);

            // if the query returned a result, create and return a Favorite object
            if ($stmt->fetch()) {
                $transaction_items = new Transaction($id, $quantity, $transaction_id, $product_id);
                $stmt->close();
                return $transaction_items;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // delete the transaction items from the database
        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM transactions WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>