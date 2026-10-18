<?php

session_start();

require_once "../config/database.php";

$message = "";


// =========================================
// REDIRECT IF ADMIN ALREADY LOGGED IN
// =========================================

if (
    isset($_SESSION["user_id"]) &&
    isset($_SESSION["role"]) &&
    $_SESSION["role"] === "admin"
) {

    header("Location: dashboard.php");
    exit();

}


// =========================================
// ADMIN LOGIN
// =========================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    if (empty($email) || empty($password)) {


        $message = "
            <div class='alert alert-danger'>
                Please enter email and password.
            </div>
        ";


    } else {


        // Get only admin user

        $stmt = $conn->prepare(

            "SELECT id, name, email, password, role
             FROM users
             WHERE email = ?
             AND role = 'admin'
             LIMIT 1"

        );


        $stmt->bind_param(
            "s",
            $email
        );


        $stmt->execute();

        $result = $stmt->get_result();


        if ($result->num_rows === 1) {


            $admin = $result->fetch_assoc();


            // Verify hashed password

            if (
                password_verify(
                    $password,
                    $admin["password"]
                )
            ) {


                // Create Session

                $_SESSION["user_id"] = $admin["id"];

                $_SESSION["name"] = $admin["name"];

                $_SESSION["email"] = $admin["email"];

                $_SESSION["role"] = $admin["role"];


                header("Location: dashboard.php");

                exit();


            } else {


                $message = "
                    <div class='alert alert-danger'>
                        Invalid email or password.
                    </div>
                ";


            }


        } else {


            $message = "
                <div class='alert alert-danger'>
                    Admin account not found.
                </div>
            ";


        }


    }


}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Login - CabGo</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <style>

        body {

            min-height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;

            background: linear-gradient(
                135deg,
                #0b1c2c,
                #163b5c
            );

        }


        .login-card {

            width: 100%;

            max-width: 420px;

            border: none;

            border-radius: 15px;

            box-shadow:
                0 10px 40px rgba(0,0,0,0.25);

        }


        .logo {

            font-size: 32px;

            font-weight: bold;

            color: #ffc107;

        }


        .admin-icon {

            width: 70px;

            height: 70px;

            margin: auto;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background: #ffc107;

            font-size: 32px;

        }

    </style>


</head>


<body>


<div class="card login-card">


    <div class="card-body p-5 text-center">


        <div class="admin-icon mb-3">

            <i class="bi bi-shield-lock-fill"></i>

        </div>


        <div class="logo mb-2">

            CabGo

        </div>


        <h5 class="mb-4 text-muted">

            Admin Panel Login

        </h5>


        <?php echo $message; ?>


        <form method="POST">


            <!-- EMAIL -->

            <div class="mb-3 text-start">


                <label class="form-label">

                    Email Address

                </label>


                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="admin@example.com"
                    required
                >


            </div>



            <!-- PASSWORD -->

            <div class="mb-4 text-start">


                <label class="form-label">

                    Password

                </label>


                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter password"
                    required
                >


            </div>



            <!-- LOGIN BUTTON -->

            <button
                type="submit"
                class="btn btn-warning w-100"
            >

                <i class="bi bi-box-arrow-in-right"></i>

                Login as Admin

            </button>


        </form>


        <hr>


        <a
            href="../index.php"
            class="text-decoration-none"
        >

            ← Back to Website

        </a>


    </div>


</div>


</body>

</html>