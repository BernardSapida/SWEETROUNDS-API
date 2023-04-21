<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "sweetrounds";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $orders = json_encode(array("name" => "Bernard Sapida"));
    $sql = "INSERT INTO `cart_items` (orders, user_id) VALUES ('$orders', 1);";
    // $sql = "INSERT INTO `users` (fullname, email, password, auth_provider, status) VALUES ('bernard sapida', 'bernardsapida1706@gmail.com', '@Password123', 'credentials', 'active')";
    // $sql = "INSERT INTO `admins` 
    // SET employee_id = 143, 
    // employee_firstname = 'noely', 
    // employee_lastname = 'sapida', 
    // username = 'noelysapida', 
    // password='@password123', 
    // role='cashier' 
    // where `id` = 1";
    $conn->query($sql);

    echo "Connected successfully";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Hello</h1>
</body>
</html>