<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class Order {
        private $id;
        private $order_number;
        private $items;
        private $donut_quantity;
        private $note;
        private $tax;
        private $shipping_fee;
        private $discount;
        private $total;
        private $payment_status;
        private $order_status;
        private $user_id;
        private $order_detail_id;

        // constructor
        public function __construct($id = null, $order_number = null, $items = null, $donut_quantity = null, $note = null, $tax = null, $shipping_fee = null, $discount = null, $total = null, $payment_status = null, $order_status = null,  $user_id = null,  $order_detail_id = null) {
            $this->id = $id;
            $this->order_number = $order_number;
            $this->items = $items;
            $this->donut_quantity = $donut_quantity;
            $this->note = $note;
            $this->tax = $tax;
            $this->shipping_fee = $shipping_fee;
            $this->discount = $discount;
            $this->total = $total;
            $this->payment_status = $payment_status;
            $this->order_status = $order_status;
            $this->user_id = $user_id;
            $this->order_detail_id = $order_detail_id;
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

        public function getDonutQuantity() {
            return $this->donut_quantity;
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

        public function getInfoId() {
            return $this->user_id;
        }

        public function getOrderId() {
            return $this->order_detail_id;
        }

        public function getOrderHistoryDetails() {
            $orderHistory = array(
                "id" => $this->id, 
                "order_number" => $this->order_number, 
                "payment_status" => $this->payment_status, 
                "user_id" => $this->user_id, 
                "order_detail_id" => $this->order_detail_id, 
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

        public function setDonutQuantity($donut_quantity) {
            $this->donut_quantity = $donut_quantity;
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

        public function setOrderStatus($order_status) {
            $this->order_status = $order_status;
        }

        public function setPaymentStatus($payment_status) {
            $this->payment_status = $payment_status;
        }

        public function setUserId($user_id) {
            $this->user_id = $user_id;
        }

        public function setUserInfoId($order_detail_id) {
            $this->order_detail_id = $order_detail_id;
        }
        
        // save the order to the database
        public function save() {
            global $mysqli;

            // if the order has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE orders SET order_number=?, items=?, donut_quantity, note=?, tax=?, shipping_fee=?, discount=?, total=?, payment_status=?, order_status=?, user_id=?, order_detail_id=? WHERE id=?");
                $stmt->bind_param("isisiiiissisi", $this->order_number, $this->items, $this->donut_quantity, $this->note, $this->tax, $this->shipping_fee, $this->discount, $this->total, $this->payment_status, $this->order_status, $this->user_id, $this->order_detail_id, $this->id);
            }

            // otherwise, insert a new record for the order
            else {
                $stmt = $mysqli->prepare("INSERT INTO orders (order_number, items, donut_quantity, note, tax, shipping_fee, discount, total, payment_status, order_status, user_id, order_detail_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isisiiiissis", $this->order_number, $this->items, $this->donut_quantity, $this->note, $this->tax, $this->shipping_fee, $this->discount, $this->total, $this->payment_status, $this->order_status, $this->user_id, $this->order_detail_id);
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

            $stmt = $mysqli->prepare("SELECT id, order_number, items, donut_quantity, note, tax, shipping_fee, discount, total, payment_status, order_status, user_id, order_detail_id FROM orders WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $order_number, $items, $donut_quantity, $note, $tax, $shipping_fee, $discount, $total, $payment_status, $order_status, $user_id, $order_detail_id);

            // if the query returned a result, create and return a Order History object
            if ($stmt->fetch()) {
                $order = new Order($id, $order_number, $items, $donut_quantity, $note, $tax, $shipping_fee, $discount, $total, $payment_status, $order_status, $user_id, $order_detail_id);
                $stmt->close();
                return $order;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // get order list
        public static function getOrders() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM orders INNER JOIN order_details on orders.order_detail_id = order_details.id");
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

        // get order list
        public static function getPageTotal() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT count(id) as `Total Page` FROM orders");
            $stmt->execute();
            $stmt->bind_result($totalPage);
            $stmt->fetch();
            $stmt->close();

            return $totalPage;
        }

        // get all donut sold
        public static function getAllDonutSold() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(donut_quantity) FROM orders");
            $stmt->execute();
            $stmt->bind_result($donutQuantitySold);
            $stmt->fetch();
            $stmt->close();

            return $donutQuantitySold;
        }

        // get day donut sold
        public static function getDayDonutSold($day) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(donut_quantity) FROM orders WHERE DATE_FORMAT(created_at, '%d')=?");
            $stmt->bind_param("i", $day);
            $stmt->execute();
            $stmt->bind_result($donutQuantitySold);
            $stmt->fetch();
            $stmt->close();

            return $donutQuantitySold;
        }

        // get week donut sold
        public static function getWeekDonutSold($year, $month, $week) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(donut_quantity) FROM orders WHERE YEAR(created_at)=? AND MONTH(created_at)=? AND WEEK(created_at, 0)=?");
            $stmt->bind_param("iii", $year, $month, $week);
            $stmt->execute();
            $stmt->bind_result($donutQuantitySold);
            $stmt->fetch();
            $stmt->close();

            return $donutQuantitySold;
        }

        // get month donut sold
        public static function getMonthDonutSold($year, $month) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(donut_quantity) FROM orders WHERE YEAR(created_at)=? AND MONTH(created_at)=?");
            $stmt->bind_param("ii", $year, $month);
            $stmt->execute();
            $stmt->bind_result($donutQuantitySold);
            $stmt->fetch();
            $stmt->close();

            return $donutQuantitySold;
        }

        // get month donut sold
        public static function getYearDonutSold($year) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(donut_quantity) FROM orders WHERE YEAR(created_at)=?");
            $stmt->bind_param("i", $year);
            $stmt->execute();
            $stmt->bind_result($donutQuantitySold);
            $stmt->fetch();
            $stmt->close();

            return $donutQuantitySold;
        }

        // load 10 orders 
        public static function loadTenOrders($rowStart) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM orders INNER JOIN order_details on orders.order_detail_id = order_details.id LIMIT 10 OFFSET $rowStart;");
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = array();

            // Add each record in result to rows
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }

        // search orders 
        public static function searchOrder($key, $rowStart) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM orders INNER JOIN order_details on orders.order_detail_id = order_details.id 
            WHERE order_number LIKE '%$key%' OR 
            firstname LIKE '%$key%' OR
            lastname LIKE '%$key%' OR
            donut_quantity LIKE '%$key%' OR
            total LIKE '%$key%' OR
            order_status LIKE '%$key%' LIMIT 10 OFFSET $rowStart;");
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = array();

            // Add each record in result to rows
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }

        // search orders 
        public static function getSearchOrderNPage($key) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM orders INNER JOIN order_details on orders.order_detail_id = order_details.id 
            WHERE order_number LIKE '%$key%' OR 
            firstname LIKE '%$key%' OR
            lastname LIKE '%$key%' OR
            donut_quantity LIKE '%$key%' OR
            total LIKE '%$key%' OR
            order_status LIKE '%$key%'");
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = array();

            // Add each record in result to rows
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }

        // get user order list
        public static function getUserOrders($userId) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM orders INNER JOIN order_details on orders.order_detail_id = order_details.id WHERE user_id=?");
            $stmt->bind_param("i", $userId);
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