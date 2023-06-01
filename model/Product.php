<?php
    require_once dirname(__DIR__)."/utils/database.php";

    class Product {
        private $id;
        private $product_number;
        private $name;
        private $flavor;
        private $price;
        private $quantity;
        private $image;
        private $availability;

        // constructor
        public function __construct($id = null, $product_number = null, $name = null,  $flavor = null,  $price = null,  $quantity = null,  $image = null, $availability = null) {
            $this->id = $id;
            $this->product_number = $product_number;
            $this->name = $name;
            $this->flavor = $flavor;
            $this->price = $price;
            $this->quantity = $quantity;
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
                "quantity" => $this->quantity,
                "image" => $this->image, 
                "availability" => $this->availability
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
                $stmt = $mysqli->prepare("UPDATE products SET product_number=?, name=?, flavor=?, price=?, quantity=?, image=?, availability=? WHERE id=?");
                $stmt->bind_param("sssiissi", $this->product_number, $this->name, $this->flavor, $this->price, $this->quantity, $this->image, $this->availability, $this->id);
            }

            // otherwise, insert a new record for the product
            else {
                $stmt = $mysqli->prepare("INSERT INTO products (product_number, name, flavor, price, quantity, image, availability) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssiiss", $this->product_number, $this->name, $this->flavor, $this->price, $this->quantity, $this->image, $this->availability);
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

        // *** load a product from the database by ID
        public static function loadById($id) {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT id, product_number, name, flavor, price, quantity, image, availability FROM products WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $product_number, $name, $flavor, $price, $quantity, $image, $availability);

            // if the query returned a result, create and return a Product object
            if ($stmt->fetch()) {
                $product = new Product($id, $product_number, $name, $flavor, $price, $quantity, $image, $availability);
                $stmt->close();
                return $product;
            }

            // otherwise, return null
            else {
                $stmt->close();
                return null;
            }
        }

        // *** load top 20 low quantity donut
        public static function loadTop20LowQuantityDonut() {
            global $mysqli;

            $stmt = $mysqli->prepare("SELECT * FROM products WHERE quantity <= 20 ORDER BY quantity;");
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = array();

            // Add each record in result to rows
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }

        // *** load donuts and its total sale DESCENDING
        public static function loadDonutTotalSale() {
            global $mysqli;

            $stmt = $mysqli->prepare(
                "SELECT *, SUM(C.quantity_sold * p.price) AS 'Total Sale' 
                FROM `products` AS p
                LEFT JOIN (
                    SELECT `product_id`, SUM(quantity_sold) AS 'quantity_sold'
                    FROM (
                        SELECT `product_id`, SUM(quantity) AS 'quantity_sold'
                        FROM order_items
                        GROUP BY `product_id`
                        UNION ALL
                        SELECT `product_id`, SUM(quantity) AS 'quantity_sold'
                        FROM transaction_items
                        GROUP BY `product_id`
                    ) AS subquery
                    GROUP BY `product_id`
                ) AS C ON p.id = C.product_id
                GROUP BY p.id
                ORDER BY `Total Sale` DESC;"
            );
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = array();

            // Add each record in result to rows
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }

        // *** load top 10 donut 
        public static function loadTop10Donut() {
            global $mysqli;

            $stmt = $mysqli->prepare(
                "SELECT products.id, sum(transaction_items.quantity) AS quantity_sold, products.image, products.name, products.price
                FROM `transaction_items` LEFT JOIN products ON products.id = transaction_items.product_id 
                GROUP BY product_id 
                ORDER BY quantity_sold DESC
                LIMIT 10;"
            );
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = array();

            // Add each record in result to rows
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }

        // ***
        public static function getProducts() {
            global $mysqli;

            $stmt = $mysqli->prepare(
                "SELECT * 
                FROM `products` AS p
                LEFT JOIN (
                    SELECT `product_id`, SUM(quantity_sold) AS 'quantity_sold'
                    FROM (
                        SELECT `product_id`, SUM(quantity) AS 'quantity_sold'
                        FROM order_items
                        GROUP BY `product_id`
                        UNION ALL
                        SELECT `product_id`, SUM(quantity) AS 'quantity_sold'
                        FROM transaction_items
                        GROUP BY `product_id`
                    ) AS subquery
                    GROUP BY `product_id`
                ) AS C ON p.id = C.product_id;"
            );
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

        // ***
        public static function getProductsForUser($id) {
            global $mysqli;

            $stmt = $mysqli->prepare(
                "SELECT 
                    P.id AS 'product_id', 
                    P.product_number, 
                    P.name, 
                    P.flavor, 
                    P.price, 
                    P.quantity, 
                    P.image, 
                    P.availability, 
                    F.id AS 'favorite_id', 
                    C.id AS 'cart_id',
                CASE
                    WHEN F.product_id IS NULL THEN FALSE
                    ELSE TRUE
                END AS 'in_favorite',
                CASE
                    WHEN C.product_id IS NULL THEN FALSE
                    ELSE TRUE
                END AS 'in_cart'
                FROM products AS P
                LEFT JOIN (SELECT product_id, id FROM favorites WHERE user_id = ?) AS F ON P.id = F.product_id
                LEFT JOIN (SELECT product_id, id FROM cart_items WHERE cart_items.user_id = ?) AS C ON P.id = C.product_id;"
            );
            $stmt->bind_param("ii", $id, $id);
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

        // ***
        public static function getUserFavorites($id) {
            global $mysqli;

            $stmt = $mysqli->prepare(
                "SELECT * FROM (SELECT 
                    P.id AS 'product_id', 
                    P.product_number, 
                    P.name, 
                    P.flavor, 
                    P.price, 
                    P.quantity, 
                    P.image, 
                    P.availability, 
                    F.id AS 'favorite_id', 
                    C.id AS 'cart_id',
                CASE
                    WHEN F.product_id IS NULL THEN FALSE
                    ELSE TRUE
                END AS 'in_favorite',
                CASE
                    WHEN C.product_id IS NULL THEN FALSE
                    ELSE TRUE
                END AS 'in_cart'
                FROM products AS P
                LEFT JOIN (SELECT product_id, id FROM favorites WHERE user_id = ?) AS F ON P.id = F.product_id
                LEFT JOIN (SELECT product_id, id FROM cart_items WHERE cart_items.user_id = ?) AS C ON P.id = C.product_id) AS A
                WHERE A.in_favorite = 1;"
            );
            $stmt->bind_param("ii", $id, $id);
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

        // ***
        public static function searchUserProductByKeyword($id, $keyword) {
            global $mysqli;

            $stmt = $mysqli->prepare(
                "SELECT 
                    P.id AS 'product_id', 
                    P.product_number, 
                    P.name, 
                    P.flavor, 
                    P.price, 
                    P.quantity, 
                    P.image, 
                    P.availability, 
                    F.id AS 'favorite_id', 
                    C.id AS 'cart_id',
                CASE
                    WHEN F.product_id IS NULL THEN FALSE
                    ELSE TRUE
                END AS 'in_favorite',
                CASE
                    WHEN C.product_id IS NULL THEN FALSE
                    ELSE TRUE
                END AS 'in_cart'
                FROM products AS P
                LEFT JOIN (SELECT product_id, id FROM favorites WHERE user_id = ?) AS F ON P.id = F.product_id
                LEFT JOIN (SELECT product_id, id FROM cart_items WHERE cart_items.user_id = ?) AS C ON P.id = C.product_id
                WHERE CONCAT_WS(' ', p.name, p.flavor, p.price)
                LIKE '%$keyword%';"
            );
            $stmt->bind_param("ii", $id, $id);
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

        // *** search orders 
        public static function searchProduct($keyword) {
            global $mysqli;

            $stmt = $mysqli->prepare(
                "SELECT * 
                FROM `products` AS p
                LEFT JOIN (
                    SELECT `product_id`, SUM(quantity_sold) AS 'quantity_sold'
                    FROM (
                        SELECT `product_id`, SUM(quantity) AS 'quantity_sold'
                        FROM order_items
                        GROUP BY `product_id`
                        UNION ALL
                        SELECT `product_id`, SUM(quantity) AS 'quantity_sold'
                        FROM transaction_items
                        GROUP BY `product_id`
                    ) AS subquery
                    GROUP BY `product_id`
                ) AS C ON p.id = C.product_id
                WHERE CONCAT_WS(' ', p.product_number, p.name, p.flavor, p.price, p.quantity, p.availability) 
                LIKE '%$keyword%';"
            );
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = array();

            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }

        public function delete() {
            global $mysqli;

            $stmt = $mysqli->prepare("DELETE FROM products WHERE id=?");
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }
?>