<?php

require_once "../includes/auth.php";

require_once "../config/database.php";

$user_id = $_SESSION["user_id"];


// Get user information

$stmt = $conn->prepare(
    "SELECT * FROM users WHERE id = ?"
);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();


// Get total bookings

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM bookings
     WHERE user_id = ?"
);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$total_bookings =
    $stmt->get_result()->fetch_assoc()["total"];


// Pending bookings

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM bookings
     WHERE user_id = ?
     AND status = 'Pending'"
);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$pending_bookings =
    $stmt->get_result()->fetch_assoc()["total"];


// Completed bookings

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM bookings
     WHERE user_id = ?
     AND status = 'Completed'"
);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$completed_bookings =
    $stmt->get_result()->fetch_assoc()["total"];


// Get recent bookings

$stmt = $conn->prepare(
    "SELECT bookings.*, cabs.cab_number, cabs.cab_type
     FROM bookings

     JOIN cabs
     ON bookings.cab_id = cabs.id

     WHERE bookings.user_id = ?

     ORDER BY bookings.id DESC

     LIMIT 5"
);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$recent_bookings = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>User Dashboard - CabGo</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <style>

        body {

            background: #f4f6f9;

            font-family: Arial, sans-serif;

        }


        /* Sidebar */

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

            color: #000;

        }


        .sidebar i {

            margin-right: 10px;

        }


        /* Main Content */

        .main-content {

            margin-left: 250px;

            padding: 30px;

        }


        /* Topbar */

        .topbar {

            background: white;

            padding: 18px 25px;

            border-radius: 10px;

            margin-bottom: 30px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            box-shadow: 0 2px 10px rgba(0,0,0,0.05);

        }


        /* Dashboard Cards */

        .dashboard-card {

            border: none;

            border-radius: 12px;

            padding: 20px;

            color: white;

            height: 100%;

        }


        .card-yellow {

            background: #ffc107;

            color: #000;

        }


        .card-blue {

            background: #0b1c2c;

        }


        .card-green {

            background: #198754;

        }


        .card-icon {

            font-size: 40px;

            opacity: 0.8;

        }


        /* Table */

        .table-container {

            background: white;

            padding: 25px;

            border-radius: 12px;

            margin-top: 30px;

            box-shadow: 0 2px 10px rgba(0,0,0,0.05);

        }


        /* Mobile */

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


<!-- Sidebar -->

<div class="sidebar">


    <div class="logo">

        <i class="bi bi-taxi-front-fill"></i>

        <span>CabGo</span>

    </div>


    <a href="dashboard.php"
        class="active">

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


    <a href="profile.php">

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



<!-- Main Content -->

<div class="main-content">


    <!-- Topbar -->

    <div class="topbar">

        <div>

            <h4 class="mb-0">

                Welcome,
                <?php echo htmlspecialchars($user["name"]); ?>

            </h4>

            <small class="text-muted">

                Manage your rides and bookings

            </small>

        </div>


        <div>

            <i class="bi bi-person-circle fs-3"></i>

        </div>

    </div>



    <!-- Dashboard Heading -->

    <h3 class="mb-4">

        Dashboard Overview

    </h3>



    <!-- Statistics Cards -->

    <div class="row g-4">


        <!-- Total Bookings -->

        <div class="col-md-4">

            <div class="dashboard-card card-yellow">

                <div class="d-flex justify-content-between">

                    <div>

                        <p>Total Bookings</p>

                        <h2>

                            <?php echo $total_bookings; ?>

                        </h2>

                    </div>


                    <div class="card-icon">

                        <i class="bi bi-calendar-check"></i>

                    </div>

                </div>

            </div>

        </div>



        <!-- Pending -->

        <div class="col-md-4">

            <div class="dashboard-card card-blue">

                <div class="d-flex justify-content-between">

                    <div>

                        <p>Pending Rides</p>

                        <h2>

                            <?php echo $pending_bookings; ?>

                        </h2>

                    </div>


                    <div class="card-icon">

                        <i class="bi bi-clock"></i>

                    </div>

                </div>

            </div>

        </div>



        <!-- Completed -->

        <div class="col-md-4">

            <div class="dashboard-card card-green">

                <div class="d-flex justify-content-between">

                    <div>

                        <p>Completed Rides</p>

                        <h2>

                            <?php echo $completed_bookings; ?>

                        </h2>

                    </div>


                    <div class="card-icon">

                        <i class="bi bi-check-circle"></i>

                    </div>

                </div>

            </div>

        </div>


    </div>



    <!-- Quick Action -->

    <div class="mt-4">

        <a href="book-cab.php"
            class="btn btn-warning">

            <i class="bi bi-plus-circle"></i>

            Book a New Cab

        </a>

    </div>



    <!-- Recent Bookings -->

    <div class="table-container">


        <div class="d-flex justify-content-between mb-3">

            <h5>

                Recent Bookings

            </h5>


            <a href="my-bookings.php">

                View All

            </a>

        </div>


        <div class="table-responsive">

            <table class="table table-hover">


                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Cab</th>

                        <th>Pickup</th>

                        <th>Drop</th>

                        <th>Fare</th>

                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>


                <?php if ($recent_bookings->num_rows > 0): ?>


                    <?php while ($booking = $recent_bookings->fetch_assoc()): ?>


                        <tr>

                            <td>

                                #<?php echo $booking["id"]; ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $booking["cab_type"]
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $booking["pickup_location"]
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $booking["drop_location"]
                                );
                                ?>

                            </td>


                            <td>

                                ₹<?php echo $booking["fare"]; ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $booking["status"]
                                );
                                ?>

                            </td>

                        </tr>


                    <?php endwhile; ?>


                <?php else: ?>


                    <tr>

                        <td colspan="6"
                            class="text-center text-muted">

                            No bookings found.

                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>


            </table>

        </div>


    </div>


</div>


</body>

</html>