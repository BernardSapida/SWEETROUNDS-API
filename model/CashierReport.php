<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class CashierReport {
        // get revenue report list by day
        public static function getDayAverageSale($date) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT AVG(total) as 'Average Sale' FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE DATE(transactions.created_at) = ?");
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
        public static function getWeekAverageSale($year, $month, $week) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT AVG(total) as 'Average Sale' FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE YEAR(transactions.created_at)=? AND MONTH(transactions.created_at)=? AND WEEK(transactions.created_at, 0)=?");
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

        // get revenue report list by month
        public static function getMonthAverageSale($year, $month) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT AVG(total) as 'Average Sale' FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE YEAR(transactions.created_at)=? AND MONTH(transactions.created_at)=?");
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
        public static function getCashierTransactions($id) {
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
        public static function getCashierTransactionByDay($id, $day) {
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
        public static function getCashierTransactionByWeek($id, $year, $month, $week) {
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
        public static function getCashierTransactionByMonth($id, $year, $month) {
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
        public static function getCashierTransactionByYear($id, $year) {
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
        public static function getCashierAllRevenue() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id");
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
        public static function getCashierRevenue($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=?");
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
        public static function getCashierDayRevenue($id, $day) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=? AND DATE_FORMAT(transactions.created_at, '%d')=?");
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
        public static function getCashierWeekRevenue($id, $year, $month, $week) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=? AND YEAR(transactions.created_at)=? AND MONTH(transactions.created_at)=? AND WEEK(transactions.created_at, 0)=?");
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
        public static function getCashierMonthRevenue($id, $year, $month) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=? AND YEAR(transactions.created_at)=? AND MONTH(transactions.created_at)=?");
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
        public static function getCashierYearRevenue($id, $year) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE admins.id=? AND YEAR(transactions.created_at)=?");
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

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id");
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
        public static function getDayRevenue($date) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(total) as revenue FROM transactions WHERE DATE(created_at) = ?");
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
        public static function getWeekRevenue($year, $month, $week) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE YEAR(transactions.created_at)=? AND MONTH(transactions.created_at)=? AND WEEK(transactions.created_at, 0)=?");
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

        // get revenue report list by month
        public static function getMonthRevenue($year, $month) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE YEAR(transactions.created_at)=? AND MONTH(transactions.created_at)=?");
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

        // get revenue report list by month
        public static function getMonthlyRevenue($year) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT
            MONTH(created_at) AS month,
            SUM(total) AS revenue
            FROM transactions
            WHERE YEAR(created_at) = ?
            GROUP BY MONTH(created_at)
            ORDER BY MONTH(created_at) ASC");

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

            $stmt = $mysqli->prepare("SELECT SUM(transactions.total) as revenue FROM admins INNER JOIN transactions ON admins.id = transactions.admin_id WHERE YEAR(transactions.created_at)=?");
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

        // get report list
        public static function getTransactions() {
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

        // get report list by day
        // Day 1-31
        public static function getTransactionByDay($day) {
            global $mysqli;
            $stmt = $mysqli->prepare("SELECT * FROM admins LEFT JOIN transactions ON admins.id = transactions.admin_id WHERE DATE(transactions.created_at)=?");
            $stmt->bind_param("s", $day);
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

            $stmt = $mysqli->prepare("SELECT * FROM admins LEFT JOIN transactions ON admins.id = transactions.admin_id WHERE YEAR(transactions.created_at)=? AND MONTH(transactions.created_at)=? AND WEEK(transactions.created_at, 0)=?");
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

            $stmt = $mysqli->prepare("SELECT * FROM admins LEFT JOIN transactions ON admins.id = transactions.admin_id WHERE YEAR(transactions.created_at)=? AND MONTH(transactions.created_at)=?");
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

            $stmt = $mysqli->prepare("SELECT * FROM admins LEFT JOIN transactions ON admins.id = transactions.admin_id WHERE YEAR(transactions.created_at)=?");
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