<?php

require_once "../includes/auth.php";
require_once "../config/database.php";
require_once "../includes/validation.php";

$user_id = $_SESSION["user_id"];

$message = "";


// ========================================
// GET CURRENT USER DATA
// ========================================

$stmt = $conn->prepare(

    "SELECT id, name, email, phone
     FROM users
     WHERE id = ?"

);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    header("Location: ../logout.php");
    exit();

}


$user = $result->fetch_assoc();


// ========================================
// UPDATE PROFILE
// ========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = normalize_phone_number($_POST["phone"] ?? "");

    if (
        empty($name) ||
        empty($email) ||
        empty($phone)
    ) {

        $message = "
            <div class='alert alert-danger'>
                All fields are required.
            </div>
        ";

    } elseif (!is_valid_email_address($email)) {

        $message = "
            <div class='alert alert-danger'>
                Please enter a valid email address.
            </div>
        ";

    } elseif (!is_valid_phone_number($phone)) {

        $message = "
            <div class='alert alert-danger'>
                Phone number must be exactly 10 digits.
            </div>
        ";

    } elseif (email_exists($conn, $email, $user_id)) {

        $message = "
            <div class='alert alert-danger'>
                This email is already registered.
            </div>
        ";

    } else {
        $update = $conn->prepare(
            "UPDATE users
             SET name = ?,
                 email = ?,
                 phone = ?
             WHERE id = ?"
        );

        $update->bind_param(
            "sssi",
            $name,
            $email,
            $phone,
            $user_id
        );

        if ($update->execute()) {
            $message = "
                <div class='alert alert-success'>
                    Profile updated successfully.
                </div>
            ";

            $user["name"] = $name;
            $user["email"] = $email;
            $user["phone"] = $phone;
            $_SESSION["user_name"] = $name;
        } else {
            $message = "
                <div class='alert alert-danger'>
                    Something went wrong. Please try again.
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

    <title>Edit Profile - CabGo</title>


    <!-- Bootstrap CSS -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <style>

        body {

            background: #f4f6f9;
            font-family: Arial, sans-serif;

        }


        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {

            width: 250px;
            height: 100vh;

            background: #0b1c2c;

            position: fixed;

            color: white;

            padding: 25px 15px;

        }


        .sidebar .logo {

            font-size: 25px;
            font-weight: bold;

            color: #ffc107;

            text-align: center;

            margin-bottom: 40px;

        }


        .sidebar a {

            display: block;

            color: #ddd;

            text-decoration: none;

            padding: 13px 15px;

            margin-bottom: 8px;

            border-radius: 7px;

            transition: 0.3s;

        }


        .sidebar a:hover,
        .sidebar .active {

            background: #ffc107;
            color: black;

        }


        .sidebar i {

            margin-right: 10px;

        }


        /* =========================
           MAIN CONTENT
        ========================= */

        .main-content {

            margin-left: 250px;
            padding: 30px;

        }


        /* TOPBAR */

        .topbar {

            background: white;

            padding: 18px 25px;

            border-radius: 10px;

            margin-bottom: 30px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            box-shadow:
                0 2px 10px rgba(0,0,0,0.05);

        }


        /* EDIT CARD */

        .edit-card {

            background: white;

            border-radius: 15px;

            padding: 35px;

            box-shadow:
                0 3px 15px rgba(0,0,0,0.06);

        }


        .form-label {

            font-weight: 600;

        }


        .input-group-text {

            background: #fff3cd;
            border-color: #dee2e6;

            color: #d39e00;

        }


        /* MOBILE */

        @media(max-width: 768px) {

            .sidebar {

                width: 70px;
                padding: 20px 8px;

            }


            .sidebar span {

                display: none;

            }


            .sidebar .logo {

                font-size: 20px;

            }


            .main-content {

                margin-left: 70px;
                padding: 15px;

            }

        }

    </style>

</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">


    <div class="logo">

        <i class="bi bi-taxi-front-fill"></i>

        <span>CabGo</span>

    </div>


    <a href="dashboard.php">

        <i class="bi bi-grid"></i>

        <span>Dashboard</span>

    </a>


    <a href="book-cab.php">

        <i class="bi bi-taxi-front"></i>

        <span>Book Cab</span>

    </a>


    <a href="my-bookings.php">

        <i class="bi bi-calendar-check"></i>

        <span>My Bookings</span>

    </a>


    <a href="profile.php"
        class="active">

        <i class="bi bi-person"></i>

        <span>My Profile</span>

    </a>


    <hr>


    <a href="../logout.php"
        class="text-danger">

        <i class="bi bi-box-arrow-right"></i>

        <span>Logout</span>

    </a>


</div>



<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main-content">


    <!-- TOPBAR -->

    <div class="topbar">


        <div>

            <h4 class="mb-0">

                Edit Profile

            </h4>

            <small class="text-muted">

                Update your personal information

            </small>

        </div>


        <a
            href="profile.php"
            class="btn btn-outline-secondary"
        >

            <i class="bi bi-arrow-left"></i>

            Back to Profile

        </a>


    </div>



    <!-- EDIT FORM -->

    <div class="row justify-content-center">


        <div class="col-lg-7">


            <div class="edit-card">


                <?php echo $message; ?>


                <form method="POST">


                    <!-- NAME -->

                    <div class="mb-4">


                        <label class="form-label">

                            Full Name

                        </label>


                        <div class="input-group">


                            <span class="input-group-text">

                                <i class="bi bi-person"></i>

                            </span>


                            <input

                                type="text"

                                name="name"

                                class="form-control"

                                value="<?php
                                echo htmlspecialchars(
                                    $user["name"]
                                );
                                ?>"

                                required

                            >


                        </div>


                    </div>



                    <!-- EMAIL -->

                    <div class="mb-4">


                        <label class="form-label">

                            Email Address

                        </label>


                        <div class="input-group">


                            <span class="input-group-text">

                                <i class="bi bi-envelope"></i>

                            </span>


                            <input

                                type="email"

                                name="email"

                                class="form-control"

                                value="<?php
                                echo htmlspecialchars(
                                    $user["email"]
                                );
                                ?>"

                                required

                            >


                        </div>


                    </div>



                    <!-- PHONE -->

                    <div class="mb-4">


                        <label class="form-label">

                            Phone Number

                        </label>


                        <div class="input-group">


                            <span class="input-group-text">

                                <i class="bi bi-telephone"></i>

                            </span>


                            <input

                                type="tel"

                                name="phone"

                                class="form-control"

                                value="<?php
                                echo htmlspecialchars(
                                    $user["phone"]
                                );
                                ?>"

                                inputmode="numeric"
                                pattern="[0-9]{10}"
                                maxlength="10"
                                required

                            >


                        </div>


                    </div>



                    <!-- BUTTONS -->

                    <div class="d-flex gap-2">


                        <button
                            type="submit"
                            class="btn btn-warning"
                        >

                            <i class="bi bi-check-circle"></i>

                            Save Changes

                        </button>


                        <a
                            href="profile.php"
                            class="btn btn-secondary"
                        >

                            Cancel

                        </a>


                    </div>


                </form>


            </div>


        </div>


    </div>


</div>


</body>

</html>