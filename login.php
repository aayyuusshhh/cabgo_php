<?php

session_start();

require_once "config/database.php";
require_once "includes/validation.php";

$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    if (empty($email) || empty($password)) {

        $message = "<div class='alert alert-danger'>
                        Email and password are required.
                    </div>";

    } else {

        $stmt = $conn->prepare(
            "SELECT * FROM users WHERE email = ?"
        );

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();


        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();


            if (password_verify(
                $password,
                $user["password"]
            )) {


                // Create session

                $_SESSION["user_id"] = $user["id"];

                $_SESSION["name"] = $user["name"];

                $_SESSION["user_email"] = $user["email"];

                $_SESSION["role"] = $user["role"];


                // Redirect based on role

                if ($user["role"] == "admin") {

                    header(
                        "Location: admin/dashboard.php"
                    );

                    exit();

                } else {

                    header(
                        "Location: user/dashboard.php"
                    );

                    exit();

                }

            } else {

                $message = "<div class='alert alert-danger'>
                                Incorrect password.
                            </div>";

            }

        } else {

            $message = "<div class='alert alert-danger'>
                            User not found.
                        </div>";

        }

    }

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">


    <title>Login - CabGo</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <style>

        body {

            background:
                linear-gradient(
                    rgba(11, 28, 44, 0.85),
                    rgba(11, 28, 44, 0.85)
                ),
                url("https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1600&q=80");

            background-size: cover;

            background-position: center;

            min-height: 100vh;

            display: flex;

            align-items: center;

        }


        .login-box {

            background: white;

            padding: 40px;

            border-radius: 12px;

            width: 100%;

            max-width: 430px;

            margin: auto;

            box-shadow:
                0 10px 40px rgba(0,0,0,0.3);

        }


        .logo {

            text-align: center;

            margin-bottom: 25px;

        }


        .logo h2 {

            color: #0b1c2c;

            font-weight: bold;

        }


        .logo i {

            color: #ffc107;

        }


        .btn-login {

            background: #ffc107;

            border: none;

            font-weight: bold;

            height: 45px;

        }


        .btn-login:hover {

            background: #e0a800;

        }
       
    </style>

</head>

<script src="assets/js/script.js"></script>
<body>


<div class="login-box">


    <div class="logo">

        <h2>

            <i class="bi bi-taxi-front-fill"></i>

            CabGo

        </h2>

        <p class="text-muted">
            Login to your account
        </p>

    </div>


    <?php echo $message; ?>


    <form method="POST">


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

                Password

            </label>

            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Enter your password"
                required>

        </div>



        <button
            type="submit"
            class="btn btn-login w-100">

            Login

        </button>


    </form>


    <p class="text-center mt-4">

        Don't have an account?

        <a href="register.php">

            Register

        </a>

    </p>


    <p class="text-center">

        <a href="index.php">

            ← Back to Home

        </a>

    </p>


</div>


</body>

</html>
