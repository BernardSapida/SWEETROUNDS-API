<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class Product {
        private $id;
        private $product_number;
        private $name;
        private $flavor;
        private $price;
        private $quantity;
        private $quantitySold;
        private $image;
        private $availability;

        // constructor
        public function __construct($id = null, $product_number = null, $name = null,  $flavor = null,  $price = null,  $quantity = null,  $quantitySold = null,  $image = null, $availability = null) {
            $this->id = $id;
            $this->product_number = $product_number;
            $this->name = $name;
            $this->flavor = $flavor;
            $this->price = $price;
            $this->quantity = $quantity;
            $this->quantitySold = $quantitySold;
            $this->image = $image;
            $this->availability = $availability;
        }

        // getters and setters
        public function getId() {
            return $this->id;
        }

        public function getProductNumber() {
            return $this->product_number;
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

        public function getQuantity() {
            return $this->quantity;
        }

        public function getQuantitySold() {
            return $this->quantitySold;
        }

        public function getImage() {
            return $this->image;
        }

        public function getAvailability() {
            return $this->availability;
        }

        public function getProductDetails() {
            $product = array(
                "id" => $this->id, 
                "product_number" => $this->product_number,  
                "name" => $this->name, 
                "flavor" => $this->flavor, 
                "price" => $this->price, 
            );

            return $product;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setProductNumber($product_number) {
            $this->product_number = $product_number;
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

        public function setQuantity($quantity) {
            $this->quantity = $quantity;
        }

        public function setQuantitySold($quantitySold) {
            $this->quantitySold += $quantitySold;
        }

        public function setImage($image) {
            $this->image = $image;
        }

        public function setAvailability($availability) {
            $this->availability = $availability;
        }
        
        // save the product to the database
        public function save() {
            global $mysqli;

            // if the product has an ID, update their record in the database
            if ($this->id) {
                $stmt = $mysqli->prepare("UPDATE products SET product_number=?, name=?, flavor=?, price=?, quantity=?, quantity_sold=?, image=?, availability=? WHERE id=?");
                $stmt->bind_param("sssiiissi", $this->product_number, $this->name, $this->flavor, $this->price, $this->quantity, $this->quantitySold, $this->image, $this->availability, $this->id);
            }

            // otherwise, insert a new record for the product
            else {
                $stmt = $mysqli->prepare("INSERT INTO products (product_number, name, flavor, price, quantity, quantity_sold, image, availability) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssiiiss", $this->product_number, $this->name, $this->flavor, $this->price, $this->quantity, $this->quantitySold, $this->image, $this->availability);
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

            $stmt = $mysqli->prepare("SELECT id, product_number, name, flavor, price, quantity, quantity_sold, image, availability FROM products WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $product_number, $name, $flavor, $price, $quantity, $quantitySold, $image, $availability);

            // if the query returned a result, create and return a Product object
            if ($stmt->fetch()) {
                $product = new Product($id, $product_number, $name, $flavor, $price, $quantity, $quantitySold, $image, $availability);
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

        // search orders 
        public static function searchProduct($key) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM products
            WHERE product_number LIKE '%$key%' OR 
            name LIKE '%$key%' OR
            flavor LIKE '%$key%' OR
            price LIKE '%$key%' OR
            quantity LIKE '%$key%' OR
            quantity_sold LIKE '%$key%' OR
            availability LIKE '%$key%';");
            $stmt->execute();
            $result = $stmt->get_result();

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