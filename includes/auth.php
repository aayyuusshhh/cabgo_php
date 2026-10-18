<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Check if user is logged in

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}


// Prevent admin from accessing user pages

if ($_SESSION["role"] !== "user") {

    header("Location: ../login.php");
    exit();

}

?>