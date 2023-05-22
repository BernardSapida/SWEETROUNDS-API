<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class Transaction {
        private $id;
        private $invoice_id;
        private $note;
        private $tax;
        private $discount;
        private $admin_id;

        // constructor
        public function __construct($id = null, $invoice_id = null, $note = null, $tax = null, $discount = null,  $admin_id = null) {
            $this->id = $id;
            $this->invoice_id = $invoice_id;
            $this->note = $note;
            $this->tax = $tax;
            $this->discount = $discount;
            $this->admin_id = $admin_id;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getOrderNumber() {
            return $this->invoice_id;
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

        public function getAdminId() {
            return $this->admin_id;
        }

        public function getTransaction() {
            $transaction = array(
                "id" => $this->id, 
                "invoice_id" => $this->invoice_id, 
                "admin_id" => $this->admin_id, 
                "note" => $this->note, 
                "tax" => $this->tax, 
                "discount" => $this->discount, 
                "admin_id" => $this->admin_id, 
            );

            return $transaction;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setInvoiceId($invoice_id) {
            $this->invoice_id = $invoice_id;
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

        public function setAdminId($admin_id) {
            $this->admin_id = $admin_id;
        }
        
        // save the transaction to the database
        public function save() {
            global $mysqli;

            // if the transaction has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE transactions SET invoice_id, note=?, tax=?, discount=?, admin_id=? WHERE id=?");
                $stmt->bind_param("ssiiii", $this->invoice_id, $this->note, $this->tax, $this->discount, $this->admin_id, $this->id);
            }

            // otherwise, insert a new record for the transaction
            else {
                $stmt = $mysqli->prepare("INSERT INTO transactions (invoice_id, note, tax, discount, admin_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssiii", $this->invoice_id, $this->note, $this->tax, $this->discount, $this->admin_id);
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

            $stmt = $mysqli->prepare("SELECT id, invoice_id, note, tax, discount, admin_id FROM transactions WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $invoice_id, $note, $tax, $discount, $admin_id);

            // if the query returned a result, create and return a Favorite object
            if ($stmt->fetch()) {
                $transaction = new Transaction($id, $invoice_id, $note, $tax, $discount, $admin_id);
                $stmt->close();
                return $transaction;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }



        // * get transaction items 
        public static function getTransactionItems($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT 
            products.name, 
            products.flavor, 
            products.price, 
            products.image, 
            transaction_items.quantity 
            FROM `transaction_items` 
            LEFT JOIN `products` ON products.id = transaction_items.product_id 
            WHERE transaction_id=$id;");
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = array();

            // Add each record in result to rows
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }

        // * search orders 
        public static function searchTransaction($keyword) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT 
            transactions.invoice_id, 
            transactions.tax, 
            transactions.discount, 
            products.price, 
            transactions.created_at,
            SUM(transaction_items.quantity) AS 'quantity', 
            SUM(transaction_items.quantity * products.price + transactions.tax - transactions.discount) as 'total' 
            FROM `transaction_items` 
            LEFT JOIN `transactions` ON transactions.id = transaction_items.transaction_id
            LEFT JOIN `products` ON products.id = transaction_items.product_id 
            WHERE CONCAT_WS(' ', transactions.invoice_id, transactions.discount, transactions.tax, transactions.created_at) LIKE '%$keyword%'
            GROUP BY transaction_items.transaction_id;");
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = array();

            // Add each record in result to rows
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }

        // * get transaction list
        public static function getTransactions() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT 
                transactions.id, 
                transactions.invoice_id, 
                transactions.note, 
                transactions.tax, 
                transactions.discount, 
                transactions.admin_id, 
                products.product_number, 
                products.name, 
                products.flavor, 
                products.image, 
                products.price, 
                transactions.created_at,
                SUM(transaction_items.quantity) as 'quantity', 
                SUM(transaction_items.quantity * products.price + transactions.tax - transactions.discount) as 'total' 
                FROM `transaction_items` 
                LEFT JOIN `transactions` ON transactions.id = transaction_items.transaction_id
                LEFT JOIN `products` ON products.id = transaction_items.product_id 
                GROUP BY transaction_items.transaction_id;
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