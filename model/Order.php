<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class Order {
        private $id;
        private $order_number;
        private $note;
        private $tax;
        private $shipping_fee;
        private $discount;
        private $payment_status;
        private $order_status;
        private $user_id;

        // constructor
        public function __construct($id = null, $order_number = null, $note = null, $tax = null, $shipping_fee = null, $discount = null, $payment_status = null, $order_status = null,  $user_id = null) {
            $this->id = $id;
            $this->order_number = $order_number;
            $this->note = $note;
            $this->tax = $tax;
            $this->shipping_fee = $shipping_fee;
            $this->discount = $discount;
            $this->payment_status = $payment_status;
            $this->order_status = $order_status;
            $this->user_id = $user_id;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getOrderNumber() {
            return $this->order_number;
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

        public function getOrderHistoryDetails() {
            $orderHistory = array(
                "id" => $this->id, 
                "order_number" => $this->order_number, 
                "note" => $this->note, 
                "tax" => $this->tax, 
                "shipping_fee" => $this->shipping_fee, 
                "discount" => $this->discount, 
                "payment_status" => $this->payment_status, 
                "order_status" => $this->order_status, 
                "payment_status" => $this->payment_status, 
                "user_id" => $this->user_id, 
            );

            return $orderHistory;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setOrderNumber($order_number) {
            $this->order_number = $order_number;
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

        public function setOrderStatus($order_status) {
            $this->order_status = $order_status;
        }

        public function setPaymentStatus($payment_status) {
            $this->payment_status = $payment_status;
        }

        public function setUserId($user_id) {
            $this->user_id = $user_id;
        }
        
        // save the order to the database
        public function save() {
            global $mysqli;

            // if the order has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE orders SET order_number=?, note=?, tax=?, shipping_fee=?, discount=?, payment_status=?, order_status=?, user_id=? WHERE id=?");
                $stmt->bind_param("ssiiissii", $this->order_number, $this->note, $this->tax, $this->shipping_fee, $this->discount, $this->payment_status, $this->order_status, $this->user_id, $this->id);
            }

            // otherwise, insert a new record for the order
            else {
                $stmt = $mysqli->prepare("INSERT INTO orders (order_number, note, tax, shipping_fee, discount, payment_status, order_status, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssiiissi", $this->order_number, $this->note, $this->tax, $this->shipping_fee, $this->discount, $this->payment_status, $this->order_status, $this->user_id);
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

            $stmt = $mysqli->prepare("SELECT id, order_number, note, tax, shipping_fee, discount, payment_status, order_status, user_id FROM orders WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $order_number, $note, $tax, $shipping_fee, $discount, $payment_status, $order_status, $user_id);

            // if the query returned a result, create and return a Order History object
            if ($stmt->fetch()) {
                $order = new Order($id, $order_number, $note, $tax, $shipping_fee, $discount, $payment_status, $order_status, $user_id);
                $stmt->close();
                return $order;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // * get order list
        public static function getOrders() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT 
                order_items.quantity, 
                orders.order_number, 
                orders.id, 
                orders.note, 
                orders.tax, 
                orders.shipping_fee, 
                orders.discount, 
                orders.order_status, 
                orders.payment_status, 
                orders.user_id, 
                order_details.firstname,
                order_details.lastname, 
                order_details.address_line_1, 
                order_details.address_line_2, 
                order_details.city, 
                order_details.contact, 
                products.price,
                products.product_number,
                products.name, 
                products.flavor, 
                products.image,
                products.availability, 
                sum(order_items.quantity) as 'quantity', sum(order_items.quantity * products.price) + orders.discount - (orders.tax + orders.shipping_fee) as 'total'
                FROM order_items
                LEFT JOIN orders ON orders.id = order_items.order_id
                LEFT JOIN order_details ON orders.id = order_details.order_id
                LEFT JOIN products ON products.id = order_items.product_id
                GROUP BY order_items.product_id;
            ");
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

        // ! get all donut sold
        public static function getAllDonutSold() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(donut_quantity) FROM orders");
            $stmt->execute();
            $stmt->bind_result($donutQuantitySold);
            $stmt->fetch();
            $stmt->close();

            return $donutQuantitySold;
        }

        // ! get day donut sold
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

        // ! get week donut sold
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

        // * get month donut sold
        public static function getMonthDonutSold($year, $month) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT sum(order_items.quantity) as total_sold 
            FROM order_items 
            LEFT JOIN products ON products.id = order_items.product_id 
            INNER JOIN orders ON orders.id = order_items.order_id
            WHERE YEAR(orders.created_at)=? AND MONTH(orders.created_at)=? AND payment_status = 'Completed';");
            $stmt->bind_param("ii", $year, $month);
            $stmt->execute();
            $stmt->bind_result($donutQuantitySold);
            $stmt->fetch();
            $stmt->close();

            return $donutQuantitySold;
        }

        // ! get month donut sold
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

        // * search orders 
        public static function searchOrder($key) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT *
            FROM (
                SELECT
                order_items.quantity, 
                orders.order_number, 
                orders.id, 
                orders.note, 
                orders.tax, 
                orders.shipping_fee, 
                orders.discount, 
                orders.order_status, 
                orders.payment_status, 
                orders.user_id, 
                order_details.firstname,
                order_details.lastname, 
                order_details.address_line_1, 
                order_details.address_line_2, 
                order_details.city, 
                order_details.contact, 
                products.price,
                products.product_number,
                products.name, 
                products.flavor, 
                products.image,
                products.availability,
                sum(order_items.quantity) as 'total_quantity',
                sum(order_items.quantity * products.price) + orders.discount - (orders.tax + orders.shipping_fee) as 'total'
                FROM order_items
                LEFT JOIN orders ON orders.id = order_items.order_id
                LEFT JOIN order_details ON orders.id = order_details.order_id
                LEFT JOIN products ON products.id = order_items.product_id
                GROUP BY order_items.product_id
            ) AS result 
            WHERE CONCAT_WS(' ', result.order_number, result.firstname, result.lastname, result.quantity, result.tax, result.shipping_fee, result.discount, result.total, result.payment_status, result.order_status) LIKE '%$key%';");
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = array();

            // Add each record in result to rows
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }

        // * get user order list
        public static function getUserOrders($userId, $productNumber) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * 
                FROM `orders` 
                LEFT JOIN `order_items` ON orders.id = order_items.order_id 
                LEFT JOIN `products` ON products.id = order_items.product_id 
                WHERE user_id = ? AND order_number=?;
            ");
            $stmt->bind_param("is", $userId, $productNumber);
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