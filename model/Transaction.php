<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class Transaction {
        private $id;
        private $order_number;
        private $items;
        private $note;
        private $tax;
        private $discount;
        private $total;
        private $admin_id;

        // constructor
        public function __construct($id = null, $order_number = null, $items = null, $note = null, $tax = null, $discount = null, $total = null,  $admin_id = null) {
            $this->id = $id;
            $this->order_number = $order_number;
            $this->items = $items;
            $this->note = $note;
            $this->tax = $tax;
            $this->discount = $discount;
            $this->total = $total;
            $this->admin_id = $admin_id;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getOrderNumber() {
            return $this->order_number;
        }

        public function getItems() {
            return $this->items;
        }

        public function getNote() {
            return $this->note;
        }

        public function getTax() {
            return $this->tax;
        }

        public function getDiscount() {
            return $this->discount;
        }

        public function getTotal() {
            return $this->total;
        }

        public function getAdminId() {
            return $this->admin_id;
        }

        public function getTransaction() {
            $transaction = array(
                "id" => $this->id, 
                "items" => $this->items, 
                "admin_id" => $this->admin_id, 
                "note" => $this->note, 
                "tax" => $this->tax, 
                "discount" => $this->discount, 
                "total" => $this->total, 
                "admin_id" => $this->admin_id, 
            );

            return $transaction;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setOrderNumber($order_number) {
            $this->order_number = $order_number;
        }

        public function setItems($items) {
            $this->items = $items;
        }

        public function setNote($note) {
            $this->note = $note;
        }

        public function setTax($tax) {
            $this->tax = $tax;
        }

        public function setDiscount($discount) {
            $this->discount = $discount;
        }

        public function setTotal($total) {
            $this->total = $total;
        }

        public function setAdminId($admin_id) {
            $this->admin_id = $admin_id;
        }
        
        // save the transaction to the database
        public function save() {
            global $mysqli;

            // if the transaction has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE transactions SET order_number=?, items=?, note=?, tax=?, discount=?, total=?, admin_id=? WHERE id=?");
                $stmt->bind_param("issiiiii", $this->order_number, $this->items, $this->note, $this->tax, $this->discount, $this->total, $this->admin_id, $this->id);
            }

            // otherwise, insert a new record for the transaction
            else {
                $stmt = $mysqli->prepare("INSERT INTO transactions (order_number, items, note, tax, discount, total, admin_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issiiii", $this->order_number, $this->items, $this->note, $this->tax, $this->discount, $this->total, $this->admin_id);
            }

            // execute the prepared statement
            $stmt->execute();

            // set the transaction's ID if they were just inserted
            if (!$this->id) {
                $this->id = $mysqli->insert_id;
            }

            // close the statement
            $stmt->close();
        }

        // load a transaction from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, order_number, items, note, tax, discount, total, admin_id FROM transactions WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $order_number, $items, $note, $tax, $discount, $total, $admin_id);

            // if the query returned a result, create and return a Favorite object
            if ($stmt->fetch()) {
                $transaction = new Transaction($id, $order_number, $items, $note, $tax, $discount, $total, $admin_id);
                $stmt->close();
                return $transaction;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // get transaction list
        public static function getTransactions() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM transactions");
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

        // delete the transaction from the database
        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM transactions WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>