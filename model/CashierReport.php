<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class CashierReport {
        // get report list
        public static function getAllTransactions() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id");
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

        // get report list
        public static function getTransactions($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=?");
            $stmt->bind_param("i", $id);
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
        public static function getTransactionByDay($id, $day) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=? AND DATE_FORMAT(transactions.created_at, '%d')=?");
            $stmt->bind_param("ii", $id, $day);
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
        public static function getTransactionByWeek($id, $year, $month, $week) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=? AND YEAR(transactions.created_at)=? AND MONTH(transactions.created_at)=? AND WEEK(transactions.created_at, 0)=?");
            $stmt->bind_param("iiii", $id, $year, $month, $week);
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
        public static function getTransactionByMonth($id, $year, $month) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=? AND YEAR(transactions.created_at)=? AND MONTH(transactions.created_at)=?");
            $stmt->bind_param("iii", $id, $year, $month);
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
        public static function getTransactionByYear($id, $year) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=? AND YEAR(transactions.created_at)=?");
            $stmt->bind_param("ii", $id, $year);
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

        // get report list
        public static function getAllRevenue() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as Revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id");
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

        // get report list
        public static function getRevenue($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as Revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=?");
            $stmt->bind_param("i", $id);
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
        public static function getDayRevenue($id, $day) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as Revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=? AND DATE_FORMAT(transactions.created_at, '%d')=?");
            $stmt->bind_param("ii", $id, $day);
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
        public static function getWeekRevenue($id, $year, $month, $week) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as Revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=? AND YEAR(transactions.created_at)=? AND MONTH(transactions.created_at)=? AND WEEK(transactions.created_at, 0)=?");
            $stmt->bind_param("iiii", $id, $year, $month, $week);
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
        public static function getMonthRevenue($id, $year, $month) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as Revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=? AND YEAR(transactions.created_at)=? AND MONTH(transactions.created_at)=?");
            $stmt->bind_param("iii", $id, $year, $month);
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
        public static function getYearRevenue($id, $year) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as Revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=? AND YEAR(transactions.created_at)=?");
            $stmt->bind_param("ii", $id, $year);
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