<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Check login

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}


// Check admin role

if ($_SESSION["role"] !== "admin") {

    header("Location: ../login.php");
    exit();

}

?>