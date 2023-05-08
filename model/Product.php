<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class Product {
        private $id;
        private $name;
        private $flavor;
        private $price;

        // constructor
        public function __construct($id = null, $name = null,  $flavor = null,  $price = null) {
            $this->id = $id;
            $this->name = $name;
            $this->flavor = $flavor;
            $this->price = $price;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getName() {
            return $this->name;
        }

        public function getFlavor() {
            return $this->flavor;
        }

        public function getPrice() {
            return $this->price;
        }

        public function getProductDetails() {
            $product = array(
                "id" => $this->id, 
                "name" => $this->name, 
                "flavor" => $this->flavor, 
                "price" => $this->price, 
            );

            return $product;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setName($name) {
            $this->name = $name;
        }

        public function setFlavor($flavor) {
            $this->flavor = $flavor;
        }

        public function setPrice($price) {
            $this->price = $price;
        }
        
        // save the product to the database
        public function save() {
            global $mysqli;

            // if the product has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE products SET name=?, flavor=?, price=? WHERE id=?");
                $stmt->bind_param("ssii", $this->name, $this->flavor, $this->price, $this->id);
            }

            // otherwise, insert a new record for the product
            else {
                $stmt = $mysqli->prepare("INSERT INTO products (name, flavor, price) VALUES (?, ?, ?)");
                $stmt->bind_param("ssi", $this->name, $this->flavor, $this->price);
            }

            // execute the prepared statement
            $stmt->execute();

            // set the product's ID if they were just inserted
            if (!$this->id) {
                $this->id = $mysqli->insert_id;
            }

            // close the statement
            $stmt->close();
        }

        // load a product from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, name, flavor, price FROM products WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $name, $flavor, $price);

            // if the query returned a result, create and return a Product object
            if ($stmt->fetch()) {
                $product = new Product($id, $name, $flavor, $price);
                $stmt->close();
                return $product;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // load top 20 low quantity donut
        public static function loadTop20LowQuantityDonut() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM products ORDER BY quantity ASC LIMIT 20;");
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = array();

            // Add each record in result to rows
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }

        // load top 10 donut 
        public static function loadTop10Donut() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM products ORDER BY quantity_sold DESC LIMIT 10;");
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = array();

            // Add each record in result to rows
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }

        public static function getProducts() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM products");
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

        // delete the product from the database
        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM products WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>