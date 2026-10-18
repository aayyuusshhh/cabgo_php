<?php

require_once "config/database.php";

$name = "Admin";
$email = "admin@cabgo.com";
$phone = "9999999999";

$password = password_hash(
    "admin123",
    PASSWORD_DEFAULT
);

$role = "admin";


$stmt = $conn->prepare(

    "INSERT INTO users
    (name, email, phone, password, role)
    VALUES (?, ?, ?, ?, ?)"

);


$stmt->bind_param(

    "sssss",

    $name,
    $email,
    $phone,
    $password,
    $role

);


if ($stmt->execute()) {

    echo "Admin created successfully!";

} else {

    echo "Error: " . $stmt->error;

}

?>