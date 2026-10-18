<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";
require_once "../../includes/validation.php";

$message = "";


// ========================================
// ADD USER
// ========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = normalize_phone_number($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = $_POST["role"] ?? "";

    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($password) ||
        empty($role)
    ) {

        $message = "
            <div class='alert alert-danger'>
                All fields are required.
            </div>
        ";

    } elseif (!is_valid_email_address($email)) {

        $message = "
            <div class='alert alert-danger'>
                Invalid email address.
            </div>
        ";

    } elseif (!is_valid_phone_number($phone)) {

        $message = "
            <div class='alert alert-danger'>
                Phone number must be exactly 10 digits.
            </div>
        ";

    } elseif (email_exists($conn, $email)) {

        $message = "
            <div class='alert alert-danger'>
                Email already exists.
            </div>
        ";

    } else {
        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

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
            $hashed_password,
            $role
        );

        if ($stmt->execute()) {
            header("Location: index.php?message=added");
            exit();
        }

        $message = "
            <div class='alert alert-danger'>
                Something went wrong.
            </div>
        ";
    }
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add User - CabGo</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


</head>


<body class="bg-light">


<div class="container py-5">


    <div class="row justify-content-center">


        <div class="col-md-7">


            <div class="card shadow border-0">


                <div class="card-header bg-dark text-white">

                    <h4 class="mb-0">

                        Add New User

                    </h4>

                </div>


                <div class="card-body p-4">


                    <?php echo $message; ?>


                    <form method="POST">


                        <!-- NAME -->

                        <div class="mb-3">

                            <label class="form-label">

                                Full Name

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                required
                            >

                        </div>



                        <!-- EMAIL -->

                        <div class="mb-3">

                            <label class="form-label">

                                Email

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                required
                            >

                        </div>



                        <!-- PHONE -->

                        <div class="mb-3">

                            <label class="form-label">

                                Phone Number

                            </label>

                            <input
                                type="tel"
                                name="phone"
                                class="form-control"
                                inputmode="numeric"
                                pattern="[0-9]{10}"
                                maxlength="10"
                                required
                            >

                        </div>



                        <!-- PASSWORD -->

                        <div class="mb-3">

                            <label class="form-label">

                                Password

                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required
                            >

                        </div>



                        <!-- ROLE -->

                        <div class="mb-4">

                            <label class="form-label">

                                Role

                            </label>

                            <select
                                name="role"
                                class="form-select"
                                required
                            >

                                <option value="user">

                                    User

                                </option>

                                <option value="admin">

                                    Admin

                                </option>

                            </select>

                        </div>



                        <button
                            type="submit"
                            class="btn btn-warning"
                        >

                            Add User

                        </button>


                        <a
                            href="index.php"
                            class="btn btn-secondary"
                        >

                            Cancel

                        </a>


                    </form>


                </div>


            </div>


        </div>


    </div>


</div>


</body>

</html>