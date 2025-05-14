<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    $servername = "localhost";
    $username = "krwxaicr_sweetrounds";
    $password = "qDLYEML3stNvFtQczNPZ";
    $database = "krwxaicr_sweetrounds";

    // connect to the database
    $mysqli = new mysqli($servername, $username, $password, $database);

    // check connection
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }
?>