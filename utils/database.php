<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "sweetrounds";

    // connect to the database
    $mysqli = new mysqli($servername, $username, $password, $database);

    // check connection
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }
?>