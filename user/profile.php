<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];


// ========================================
// GET USER INFORMATION
// ========================================

$stmt = $conn->prepare(

    "SELECT id, name, email, phone, role, created_at
     FROM users
     WHERE id = ?"

);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();


// If user somehow doesn't exist

if ($result->num_rows !== 1) {

    header("Location: ../logout.php");
    exit();

}


$user = $result->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>My Profile - CabGo</title>


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


        /* PROFILE CARD */

        .profile-card {

            background: white;

            border-radius: 15px;

            padding: 40px;

            box-shadow:
                0 3px 15px rgba(0,0,0,0.06);

        }


        /* PROFILE HEADER */

        .profile-header {

            text-align: center;

            padding-bottom: 30px;

            border-bottom: 1px solid #eee;

            margin-bottom: 30px;

        }


        .profile-avatar {

            width: 100px;
            height: 100px;

            background: #ffc107;

            color: #0b1c2c;

            border-radius: 50%;

            display: flex;

            align-items: center;
            justify-content: center;

            margin: auto;

            font-size: 50px;

            margin-bottom: 15px;

        }


        /* INFO ITEMS */

        .info-item {

            padding: 18px;

            background: #f8f9fa;

            border-radius: 10px;

            margin-bottom: 15px;

        }


        .info-label {

            color: #6c757d;

            font-size: 13px;

            margin-bottom: 5px;

        }


        .info-value {

            font-size: 16px;

            font-weight: 600;

            color: #212529;

        }


        .info-icon {

            width: 45px;
            height: 45px;

            background: #fff3cd;

            color: #d39e00;

            border-radius: 10px;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 20px;

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

                My Profile

            </h4>

            <small class="text-muted">

                Manage your personal information

            </small>

        </div>


        <a
            href="edit-profile.php"
            class="btn btn-warning"
        >

            <i class="bi bi-pencil-square"></i>

            Edit Profile

        </a>


    </div>



    <!-- PROFILE CARD -->

    <div class="row justify-content-center">


        <div class="col-lg-8">


            <div class="profile-card">


                <!-- PROFILE HEADER -->

                <div class="profile-header">


                    <div class="profile-avatar">

                        <i class="bi bi-person-fill"></i>

                    </div>


                    <h3>

                        <?php
                        echo htmlspecialchars(
                            $user["name"]
                        );
                        ?>

                    </h3>


                    <p class="text-muted mb-0">

                        CabGo User

                    </p>


                </div>



                <!-- USER INFORMATION -->

                <div class="row">


                    <!-- NAME -->

                    <div class="col-md-6">


                        <div class="info-item">


                            <div class="d-flex align-items-center gap-3">


                                <div class="info-icon">

                                    <i class="bi bi-person"></i>

                                </div>


                                <div>


                                    <div class="info-label">

                                        FULL NAME

                                    </div>


                                    <div class="info-value">

                                        <?php
                                        echo htmlspecialchars(
                                            $user["name"]
                                        );
                                        ?>

                                    </div>


                                </div>


                            </div>


                        </div>


                    </div>



                    <!-- EMAIL -->

                    <div class="col-md-6">


                        <div class="info-item">


                            <div class="d-flex align-items-center gap-3">


                                <div class="info-icon">

                                    <i class="bi bi-envelope"></i>

                                </div>


                                <div>


                                    <div class="info-label">

                                        EMAIL ADDRESS

                                    </div>


                                    <div class="info-value">

                                        <?php
                                        echo htmlspecialchars(
                                            $user["email"]
                                        );
                                        ?>

                                    </div>


                                </div>


                            </div>


                        </div>


                    </div>



                    <!-- PHONE -->

                    <div class="col-md-6">


                        <div class="info-item">


                            <div class="d-flex align-items-center gap-3">


                                <div class="info-icon">

                                    <i class="bi bi-telephone"></i>

                                </div>


                                <div>


                                    <div class="info-label">

                                        PHONE NUMBER

                                    </div>


                                    <div class="info-value">

                                        <?php
                                        echo htmlspecialchars(
                                            $user["phone"]
                                        );
                                        ?>

                                    </div>


                                </div>


                            </div>


                        </div>


                    </div>



                    <!-- ROLE -->

                    <div class="col-md-6">


                        <div class="info-item">


                            <div class="d-flex align-items-center gap-3">


                                <div class="info-icon">

                                    <i class="bi bi-person-badge"></i>

                                </div>


                                <div>


                                    <div class="info-label">

                                        ACCOUNT TYPE

                                    </div>


                                    <div class="info-value">

                                        <?php
                                        echo ucfirst(
                                            htmlspecialchars(
                                                $user["role"]
                                            )
                                        );
                                        ?>

                                    </div>


                                </div>


                            </div>


                        </div>


                    </div>



                    <!-- JOIN DATE -->

                    <div class="col-md-12">


                        <div class="info-item">


                            <div class="d-flex align-items-center gap-3">


                                <div class="info-icon">

                                    <i class="bi bi-calendar"></i>

                                </div>


                                <div>


                                    <div class="info-label">

                                        MEMBER SINCE

                                    </div>


                                    <div class="info-value">

                                        <?php

                                        echo date(
                                            "d F Y",
                                            strtotime(
                                                $user["created_at"]
                                            )
                                        );

                                        ?>

                                    </div>


                                </div>


                            </div>


                        </div>


                    </div>


                </div>


            </div>


        </div>


    </div>


</div>


</body>

</html>