<?php

session_start();

require_once "config/database.php";
require_once "includes/validation.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = normalize_phone_number($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($password)
    ) {

        $message = "<div class='alert alert-danger'>
                        All fields are required.
                    </div>";

    } elseif (!is_valid_email_address($email)) {

        $message = "<div class='alert alert-danger'>
                        Invalid email address.
                    </div>";

    } elseif (!is_valid_phone_number($phone)) {

        $message = "<div class='alert alert-danger'>
                        Phone number must be exactly 10 digits.
                    </div>";

    } elseif ($password !== $confirm_password) {

        $message = "<div class='alert alert-danger'>
                        Passwords do not match.
                    </div>";

    } elseif (email_exists($conn, $email)) {

        $message = "<div class='alert alert-danger'>
                        Email already registered.
                    </div>";

    } else {

        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $role = "user";

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
            header("Location: login.php?registered=success");
            exit();
        }

        $message = "<div class='alert alert-danger'>
                        Something went wrong.
                    </div>";
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Register - CabGo</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <style>

        body {
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .register-container {
            max-width: 950px;
            width: 100%;
            margin: auto;
        }

        .register-left {
            background: #0b1c2c;
            color: white;
            padding: 60px;
            border-radius: 10px 0 0 10px;
        }

        .register-left h1 {
            color: #ffc107;
        }

        .register-right {
            background: white;
            padding: 45px;
            border-radius: 0 10px 10px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .btn-cabgo {
            background: #ffc107;
            border: none;
            font-weight: bold;
        }

        .btn-cabgo:hover {
            background: #e0a800;
        }

        @media(max-width: 768px) {

            .register-left {
                border-radius: 10px 10px 0 0;
                padding: 35px;
            }

            .register-right {
                border-radius: 0 0 10px 10px;
            }

        }

    </style>

</head>
<script src="assets/js/script.js"></script>
<body>


<div class="container register-container">

    <div class="row g-0">


        <!-- Left Side -->

        <div class="col-md-5 register-left d-flex flex-column justify-content-center">

            <h1>
                <i class="bi bi-taxi-front-fill"></i>
                CabGo
            </h1>

            <h3 class="mt-4">
                Ride with Confidence
            </h3>

            <p>
                Your safety and comfort is our priority.
                Create your account and start booking rides.
            </p>

            <a href="index.php"
                class="btn btn-outline-light mt-3">

                Back to Home

            </a>

        </div>



        <!-- Registration Form -->

        <div class="col-md-7 register-right">

            <h2 class="fw-bold">
                Create Your Account
            </h2>

            <p class="text-muted">
                Register to start booking rides.
            </p>


            <?php echo $message; ?>


            <form method="POST">


                <div class="mb-3">

                    <label class="form-label">
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="Enter your full name"
                        required>

                </div>



                <div class="mb-3">

                    <label class="form-label">
                        Email Address
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Enter your email"
                        required>

                </div>



                <div class="mb-3">

                    <label class="form-label">
                        Phone Number
                    </label>

                    <input
                        type="tel"
                        name="phone"
                        class="form-control"
                        placeholder="Enter 10-digit phone number"
                        inputmode="numeric"
                        pattern="[0-9]{10}"
                        maxlength="10"
                        required>

                </div>



                <div class="mb-3">

                    <label class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter password"
                        required>

                </div>



                <div class="mb-3">

                    <label class="form-label">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        name="confirm_password"
                        class="form-control"
                        placeholder="Confirm password"
                        required>

                </div>



                <button
                    type="submit"
                    class="btn btn-cabgo w-100">

                    Register

                </button>


            </form>


            <p class="text-center mt-4">

                Already have an account?

                <a href="login.php">
                    Login
                </a>

            </p>

        </div>


    </div>

</div>


</body>

</html>