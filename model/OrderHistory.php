<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class OrderHistory {
        private $id;
        private $order_number;
        private $payment_status;
        private $user_id;
        private $order_id;

        // constructor
        public function __construct($id = null, $order_number = null,  $payment_status = null,  $user_id = null,  $order_id = null) {
            $this->id = $id;
            $this->order_number = $order_number;
            $this->payment_status = $payment_status;
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

        public function getPaymentStatus() {
            return $this->payment_status;
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

        public function setPaymentStatus($payment_status) {
            $this->payment_status = $payment_status;
        }

        public function setUserId($user_id) {
            $this->user_id = $user_id;
        }

        public function setOrderId($order_id) {
            $this->order_id = $order_id;
        }
        
        // save the order history to the database
        public function save() {
            global $mysqli;

            // if the order history has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE order_history SET order_number=?, payment_status=?, user_id=?, order_id=? WHERE id=?");
                $stmt->bind_param("isiii", $this->order_number, $this->payment_status, $this->user_id, $this->order_id, $this->id);
            }

            // otherwise, insert a new record for the order history
            else {
                $stmt = $mysqli->prepare("INSERT INTO order_history (order_number, payment_status, user_id, order_id) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isii", $this->order_number, $this->payment_status, $this->user_id, $this->order_id);
            }

            // execute the prepared statement
            $stmt->execute();

            // set the order history's ID if they were just inserted
            if (!$this->id) {
                $this->id = $mysqli->insert_id;
            }

            // close the statement
            $stmt->close();
        }

        // load a order history from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, order_number, payment_status, user_id, order_id FROM order_history WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $order_number, $payment_status, $user_id, $order_id);

            // if the query returned a result, create and return a Order History object
            if ($stmt->fetch()) {
                $order_history = new OrderHistory($id, $order_number, $payment_status, $user_id, $order_id);
                $stmt->close();
                return $order_history;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // delete the order history from the database
        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM order_history WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>