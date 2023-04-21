<?php
    function database_query($sql) {
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

        $conn->query($sql);
    }
?>