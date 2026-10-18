<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";


// =========================================
// CHECK BOOKING ID
// =========================================

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: index.php?message=error");
    exit();

}

$booking_id = intval($_GET["id"]);


// =========================================
// CHECK BOOKING EXISTS
// =========================================

$check = $conn->prepare(

    "SELECT id FROM bookings WHERE id = ?"

);

$check->bind_param(
    "i",
    $booking_id
);

$check->execute();

$result = $check->get_result();


if ($result->num_rows !== 1) {

    header("Location: index.php?message=error");
    exit();

}


// =========================================
// DELETE BOOKING
// =========================================

$stmt = $conn->prepare(

    "DELETE FROM bookings WHERE id = ?"

);

$stmt->bind_param(
    "i",
    $booking_id
);


if ($stmt->execute()) {


    header(
        "Location: index.php?message=deleted"
    );

    exit();


} else {


    header(
        "Location: index.php?message=error"
    );

    exit();

}

?>