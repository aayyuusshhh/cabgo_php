<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";


// ========================================
// CHECK ID
// ========================================

if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"])
) {

    header("Location: index.php");
    exit();

}


$user_id = intval($_GET["id"]);


// ========================================
// PREVENT ADMIN FROM DELETING SELF
// ========================================

if ($user_id == $_SESSION["user_id"]) {

    header("Location: index.php?message=error");
    exit();

}


// ========================================
// CHECK USER EXISTS
// ========================================

$check = $conn->prepare(

    "SELECT id
     FROM users
     WHERE id = ?"

);

$check->bind_param(
    "i",
    $user_id
);

$check->execute();

$result = $check->get_result();


if ($result->num_rows !== 1) {

    header("Location: index.php?message=error");
    exit();

}


// ========================================
// DELETE USER
// ========================================

$stmt = $conn->prepare(

    "DELETE FROM users
     WHERE id = ?"

);

$stmt->bind_param(
    "i",
    $user_id
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