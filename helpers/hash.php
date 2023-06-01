<?php
    function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // ***
    function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }

    function getRandomId() {
        return join("", explode(".",uniqid(microtime(true), true)));
    }
?>