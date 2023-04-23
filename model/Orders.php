<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class Order {
        private $id;
        private $order_number;
        private $items;
        private $note;
        private $tax;
        private $shipping_fee;
        private $discount;
        private $total;
        private $payment_status;
        private $order_status;
        private $user_id;
        private $order_id;

        // constructor
        public function __construct($id = null, $order_number = null, $items = null, $note = null, $tax = null, $shipping_fee = null, $discount = null, $total = null, $payment_status = null, $order_status = null,  $user_id = null,  $order_id = null) {
            $this->id = $id;
            $this->order_number = $order_number;
            $this->items = $items;
            $this->note = $note;
            $this->tax = $tax;
            $this->shipping_fee = $shipping_fee;
            $this->discount = $discount;
            $this->total = $total;
            $this->payment_status = $payment_status;
            $this->order_status = $order_status;
            $this->user_id = $user_id;
            $this->order_id = $order_id;
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

        public function getShippingFee() {
            return $this->shipping_fee;
        }

        public function getDiscount() {
            return $this->discount;
        }

        public function getTotal() {
            return $this->total;
        }

        public function getPaymentStatus() {
            return $this->payment_status;
        }

        public function getOrderStatus() {
            return $this->order_status;
        }

        public function getUserId() {
            return $this->user_id;
        }

        public function getOrderId() {
            return $this->order_id;
        }

        public function getOrderHistoryDetails() {
            $orderHistory = array(
                "id" => $this->id, 
                "order_number" => $this->order_number, 
                "payment_status" => $this->payment_status, 
                "user_id" => $this->user_id, 
                "order_id" => $this->order_id, 
            );

            return $orderHistory;
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

        public function setShippingFee($shipping_fee) {
            $this->shipping_fee = $shipping_fee;
        }

        public function setDiscount($discount) {
            $this->discount = $discount;
        }

        public function setTotal($total) {
            $this->total = $total;
        }

        public function setPaymentStatus($payment_status) {
            $this->payment_status = $payment_status;
        }

        public function setUserId($user_id) {
            $this->user_id = $user_id;
        }

        public function setOrderId($order_id) {
            $this->order_id = $order_id;
        }
        
        // save the order to the database
        public function save() {
            global $mysqli;

            // if the order has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE orders SET order_number=?, payment_status=?, user_id=?, order_id=? WHERE id=?");
                $stmt->bind_param("isiii", $this->order_number, $this->payment_status, $this->user_id, $this->order_id, $this->id);
            }

            // otherwise, insert a new record for the order
            else {
                $stmt = $mysqli->prepare("INSERT INTO orders (order_number, payment_status, user_id, order_id) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isii", $this->order_number, $this->payment_status, $this->user_id, $this->order_id);
            }

            // execute the prepared statement
            $stmt->execute();

            // set the order's ID if they were just inserted
            if (!$this->id) {
                $this->id = $mysqli->insert_id;
            }

            // close the statement
            $stmt->close();
        }

        // load a order from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, order_number, payment_status, user_id, order_id FROM orders WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $order_number, $payment_status, $user_id, $order_id);

            // if the query returned a result, create and return a Order History object
            if ($stmt->fetch()) {
                $order = new OrderHistory($id, $order_number, $payment_status, $user_id, $order_id);
                $stmt->close();
                return $order;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // delete the order from the database
        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM orders WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>