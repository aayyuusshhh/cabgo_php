<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";


// ========================================
// CHECK CAB ID
// ========================================

if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"])
) {

    header("Location: index.php");
    exit();

}


$cab_id = intval($_GET["id"]);


// ========================================
// CHECK IF CAB EXISTS
// ========================================

$check = $conn->prepare(

    "SELECT id
     FROM cabs
     WHERE id = ?"

);


$check->bind_param(
    "i",
    $cab_id
);


$check->execute();

$result = $check->get_result();


if ($result->num_rows !== 1) {

    header("Location: index.php?message=error");
    exit();

}


// ========================================
// DELETE CAB
// NOTE: Bookings are linked with ON DELETE CASCADE
// so deleting the cab should also remove related bookings.
// ========================================

$stmt = $conn->prepare(

    "DELETE FROM cabs
     WHERE id = ?"

);


$stmt->bind_param(
    "i",
    $cab_id
);


if ($stmt->execute()) {

    header(
        "Location: index.php?message=deleted"
    );

    exit();

}


header(
    "Location: index.php?message=error"
);

exit();

?>