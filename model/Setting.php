<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class Setting {
        private $id;
        private $tax;
        private $discount;
        private $accepting_order;

        // constructor
        public function __construct($id = null, $tax = null, $discount = null,  $accepting_order = null) {
            $this->id = $id;
            $this->tax = $tax;
            $this->discount = $discount;
            $this->accepting_order = $accepting_order;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getTax() {
            return $this->tax;
        }

        public function getDiscount() {
            return $this->discount;
        }

        public function getAcceptingOrder() {
            return $this->accepting_order;
        }

        public function getSettingDetails() {
            $setting = array(
                "id" => $this->id, 
                "tax" => $this->tax, 
                "discount" => $this->discount, 
                "accepting_order" => $this->accepting_order, 
            );

            return $setting;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setTax($tax) {
            $this->tax = $tax;
        }

        public function setDiscount($discount) {
            $this->discount = $discount;
        }

        public function setAcceptingOrder($accepting_order) {
            $this->accepting_order = $accepting_order;
        }
        
        // save the setting to the database
        public function save() {
            global $mysqli;

            // if the setting has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE settings SET tax=?, discount=?, accepting_order=? WHERE id=?");
                $stmt->bind_param("iiii", $this->tax, $this->discount, $this->accepting_order, $this->id);
            }

            // otherwise, insert a new record for the setting
            else {
                $stmt = $mysqli->prepare("INSERT INTO settings (tax, discount, accepting_order) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $this->tax, $this->discount, $this->accepting_order);
            }

            // execute the prepared statement
            $stmt->execute();

            // set the setting's ID if they were just inserted
            if (!$this->id) {
                $this->id = $mysqli->insert_id;
            }

            // close the statement
            $stmt->close();
        }

        // load a setting from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, tax, discount, accepting_order FROM settings WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $tax, $discount, $accepting_order);

            // if the query returned a result, create and return a Setting object
            if ($stmt->fetch()) {
                $setting = new Setting($id, $tax, $discount, $accepting_order);
                $stmt->close();
                return $setting;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // delete the setting from the database
        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM settings WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>