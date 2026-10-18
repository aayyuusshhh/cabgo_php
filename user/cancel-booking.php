<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];


// ========================================
// CHECK BOOKING ID
// ========================================

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: my-bookings.php");
    exit();

}


$booking_id = intval($_GET["id"]);


// ========================================
// CHECK IF BOOKING BELONGS TO USER
// ========================================

$stmt = $conn->prepare(

    "SELECT id, status
     FROM bookings
     WHERE id = ?
     AND user_id = ?"

);


$stmt->bind_param(
    "ii",
    $booking_id,
    $user_id
);


$stmt->execute();

$result = $stmt->get_result();


// ========================================
// BOOKING NOT FOUND
// ========================================

if ($result->num_rows !== 1) {

    header("Location: my-bookings.php");
    exit();

}


$booking = $result->fetch_assoc();


// ========================================
// CHECK IF BOOKING CAN BE CANCELLED
// ========================================

if (

    $booking["status"] !== "Pending"

    &&

    $booking["status"] !== "Confirmed"

) {

    header("Location: my-bookings.php");
    exit();

}


// ========================================
// CANCEL BOOKING
// ========================================

$stmt = $conn->prepare(

    "UPDATE bookings
     SET status = 'Cancelled'
     WHERE id = ?
     AND user_id = ?"

);


$stmt->bind_param(
    "ii",
    $booking_id,
    $user_id
);


if ($stmt->execute()) {

    header("Location: my-bookings.php?message=cancelled");
    exit();

}


header("Location: my-bookings.php?message=error");
exit();

?>