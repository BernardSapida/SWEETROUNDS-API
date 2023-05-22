<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class OrderReport {
        public static function getCompletedOrders() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT count(*) as completed_transaction FROM orders WHERE payment_status = 'Completed'");
            $stmt->execute();
            $stmt->bind_result($completed_transaction);
            $stmt->fetch();
            $stmt->close();

            return $completed_transaction;
        }

        // get revenue report list by day
        public static function getDayAverageSale($date) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT AVG(total) as 'Average Sale' FROM orders WHERE DATE(created_at) = ? AND payment_status = 'Completed'");
            $stmt->bind_param("s", $date);
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

        // get revenue report list by week
        public static function getWeekAverageSale($year, $week) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT AVG(total) as 'Average Sale' FROM orders WHERE YEAR(orders.created_at)=? AND WEEK(orders.created_at, 0)=? AND payment_status = 'Completed'");
            $stmt->bind_param("ii", $year, $week);
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

        // get revenue report list by month
        public static function getMonthAverageSale($year, $month) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT AVG(total) as 'Average Sale' FROM orders WHERE YEAR(orders.created_at)=? AND MONTH(orders.created_at)=? AND payment_status = 'Completed'");
            $stmt->bind_param("ii", $year, $month);
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

        // get revenue report list by day
        public static function getDayCompletedOrders($date) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT count(*) as completed_transaction FROM orders WHERE DATE(created_at) = ? AND payment_status = 'Completed'");
            $stmt->bind_param("s", $date);
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

        // get revenue report list by week
        public static function getWeekCompletedOrders($year, $week) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT count(*) as completed_transaction FROM orders WHERE YEAR(orders.created_at)=? AND WEEK(orders.created_at, 0)=? AND payment_status = 'Completed'");
            $stmt->bind_param("ii", $year, $week);
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

        // * get completed orders by month
        public static function getMonthCompletedOrders($year, $month) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT count(*) as completed_transaction FROM orders WHERE YEAR(orders.created_at)=? AND MONTH(orders.created_at)=? AND payment_status = 'Completed'");
            $stmt->bind_param("ii", $year, $month);
            $stmt->execute();
            $stmt->bind_result($completed_transaction);
            $stmt->fetch();

            return $completed_transaction;
        }

        // * get number of transaction
        public static function getAllTransactions() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT count(*) as number_of_transactions
            FROM orders
            WHERE payment_status = 'Completed'");
            $stmt->execute();
            $stmt->bind_result($number_of_transaction);
            $stmt->fetch();

            return $number_of_transaction;
        }

        // * get revenue report by day
        public static function getDayRevenue($date) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT sum(order_items.quantity * products.price) + orders.tax + orders.shipping_fee - orders.discount as revenue 
                FROM order_items 
                LEFT JOIN products ON products.id = order_items.product_id 
                INNER JOIN orders ON orders.id = order_items.order_id
                WHERE DATE(orders.created_at) = ? AND payment_status = 'Completed'
            ");
            $stmt->bind_param("s", $date);
            $stmt->execute();
            $stmt->bind_result($revenue);
            $stmt->fetch();

            return $revenue;
        }

        // get revenue report list by week
        public static function getWeekRevenue($year, $week) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(orders.total) as revenue FROM orders WHERE YEAR(orders.created_at)=? AND WEEK(orders.created_at, 0)=? AND payment_status = 'Completed'");
            $stmt->bind_param("ii", $year, $week);
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

        // * get revenue report by month
        public static function getMonthRevenue($year, $month) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT sum(order_items.quantity * products.price) + orders.discount - (orders.tax + orders.shipping_fee) as revenue 
                FROM order_items 
                LEFT JOIN products ON products.id = order_items.product_id 
                INNER JOIN orders ON orders.id = order_items.order_id
                WHERE YEAR(orders.created_at)=? AND MONTH(orders.created_at)=? AND payment_status = 'Completed';
            ");
            $stmt->bind_param("ii", $year, $month);
            $stmt->execute();
            $stmt->bind_result($revenue);
            $stmt->fetch();

            return $revenue;
        }

        // * get revenue by monthly
        public static function getMonthlyRevenue($year) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT sum(order_items.quantity * products.price) + orders.discount - (orders.tax + orders.shipping_fee) as revenue 
                FROM order_items 
                LEFT JOIN products ON products.id = order_items.product_id 
                INNER JOIN orders ON orders.id = order_items.order_id
                WHERE YEAR(orders.created_at)=? AND payment_status = 'Completed'
                GROUP BY MONTH(orders.created_at)
                ORDER BY MONTH(orders.created_at) ASC;
            ");

            $stmt->bind_param("i", $year);
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

        // get revenue report list by year
        public static function getYearRevenue($year) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(orders.total) as revenue FROM orders WHERE YEAR(orders.created_at)=? AND payment_status = 'Completed'");
            $stmt->bind_param("i", $year);
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

        // get report list by day
        // Day 1-31
        public static function getTransactionByDay($day) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM orders WHERE DATE_FORMAT(orders.created_at, '%d')=? AND payment_status = 'Completed'");
            $stmt->bind_param("i", $day);
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

        // get report list by week
        public static function getTransactionByWeek($year, $month, $week) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM orders WHERE YEAR(orders.created_at)=? AND MONTH(orders.created_at)=? AND WEEK(orders.created_at, 0)=? AND payment_status = 'Completed'");
            $stmt->bind_param("iii", $year, $month, $week);
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

        // get report list by month
        public static function getTransactionByMonth($year, $month) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM orders WHERE YEAR(orders.created_at)=? AND MONTH(orders.created_at)=? AND payment_status = 'Completed'");
            $stmt->bind_param("ii", $year, $month);
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

        // get report list by year
        public static function getTransactionByYear($year) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM orders WHERE YEAR(orders.created_at)=? AND payment_status = 'Completed'");
            $stmt->bind_param("i", $year);
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
    }
?>