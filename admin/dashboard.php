<?php

require_once "../includes/admin_auth.php";
require_once "../config/database.php";


// =========================================
// TOTAL USERS
// =========================================

$users_query = $conn->query(

    "SELECT COUNT(*) AS total
     FROM users
     WHERE role = 'user'"

);

$total_users =
    $users_query->fetch_assoc()["total"];


// =========================================
// TOTAL CABS
// =========================================

$cabs_query = $conn->query(

    "SELECT COUNT(*) AS total
     FROM cabs"

);

$total_cabs =
    $cabs_query->fetch_assoc()["total"];


// =========================================
// TOTAL BOOKINGS
// =========================================

$bookings_query = $conn->query(

    "SELECT COUNT(*) AS total
     FROM bookings"

);

$total_bookings =
    $bookings_query->fetch_assoc()["total"];


// =========================================
// PENDING BOOKINGS
// =========================================

$pending_query = $conn->query(

    "SELECT COUNT(*) AS total
     FROM bookings
     WHERE status = 'Pending'"

);

$pending_bookings =
    $pending_query->fetch_assoc()["total"];


// =========================================
// RECENT BOOKINGS
// =========================================

$recent_query = $conn->prepare(

    "SELECT

        bookings.*,

        users.name AS user_name,

        cabs.cab_type,
        cabs.cab_number

     FROM bookings

     INNER JOIN users
        ON bookings.user_id = users.id

     INNER JOIN cabs
        ON bookings.cab_id = cabs.id

     ORDER BY bookings.id DESC

     LIMIT 5"

);

$recent_query->execute();

$recent_bookings = $recent_query->get_result();

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard - CabGo</title>


    <!-- Bootstrap -->

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


        /* =========================================
           SIDEBAR
        ========================================= */

        .sidebar {

            width: 250px;
            height: 100vh;

            position: fixed;

            background: #0b1c2c;

            padding: 25px 15px;

        }


        .logo {

            text-align: center;

            font-size: 25px;

            font-weight: bold;

            color: #ffc107;

            margin-bottom: 40px;

        }


        .sidebar a {

            display: block;

            color: #ddd;

            text-decoration: none;

            padding: 13px 15px;

            margin-bottom: 8px;

            border-radius: 7px;

        }


        .sidebar a:hover,
        .sidebar .active {

            background: #ffc107;

            color: #000;

        }


        .sidebar i {

            margin-right: 10px;

        }


        /* =========================================
           MAIN CONTENT
        ========================================= */

        .main-content {

            margin-left: 250px;

            padding: 30px;

        }


        .topbar {

            background: white;

            padding: 20px 25px;

            border-radius: 10px;

            margin-bottom: 30px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            box-shadow:
                0 2px 10px rgba(0,0,0,0.05);

        }


        /* =========================================
           STAT CARDS
        ========================================= */

        .stat-card {

            border: none;

            border-radius: 12px;

            box-shadow:
                0 3px 15px rgba(0,0,0,0.06);

            transition: 0.3s;

        }


        .stat-card:hover {

            transform: translateY(-5px);

        }


        .stat-icon {

            width: 55px;

            height: 55px;

            display: flex;

            justify-content: center;

            align-items: center;

            border-radius: 12px;

            font-size: 25px;

        }


        /* =========================================
           TABLE
        ========================================= */

        .table-box {

            background: white;

            padding: 25px;

            border-radius: 12px;

            box-shadow:
                0 3px 15px rgba(0,0,0,0.06);

        }


        .status {

            padding: 5px 12px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: bold;

        }


        .pending {

            background: #fff3cd;

            color: #856404;

        }


        .confirmed {

            background: #cfe2ff;

            color: #084298;

        }


        .completed {

            background: #d1e7dd;

            color: #0f5132;

        }


        .cancelled {

            background: #f8d7da;

            color: #842029;

        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media(max-width:768px) {


            .sidebar {

                width: 70px;

                padding: 20px 8px;

            }


            .sidebar span {

                display: none;

            }


            .main-content {

                margin-left: 70px;

                padding: 15px;

            }


        }

    </style>


</head>


<body>


<!-- =========================================
     SIDEBAR
========================================= -->

<div class="sidebar">


    <div class="logo">

        <i class="bi bi-taxi-front-fill"></i>

        <span>CabGo</span>

    </div>


    <a
        href="dashboard.php"
        class="active"
    >

        <i class="bi bi-grid"></i>

        <span>Dashboard</span>

    </a>


    <a href="users/index.php">

        <i class="bi bi-people"></i>

        <span>Users</span>

    </a>


    <a href="bookings/index.php">

        <i class="bi bi-calendar-check"></i>

        <span>Bookings</span>

    </a>


    <a href="cabs/index.php">

        <i class="bi bi-taxi-front"></i>

        <span>Manage Cabs</span>

    </a>


    <hr>


    <a
        href="../logout.php"
        class="text-danger"
    >

        <i class="bi bi-box-arrow-right"></i>

        <span>Logout</span>

    </a>


</div>



<!-- =========================================
     MAIN CONTENT
========================================= -->

<div class="main-content">


    <!-- TOPBAR -->

    <div class="topbar">


        <div>


            <h4 class="mb-1">

                Welcome,

                <?php

                echo htmlspecialchars(
                    $_SESSION["name"]
                );

                ?>

            </h4>


            <small class="text-muted">

                Admin Dashboard Overview

            </small>


        </div>


        <div class="text-muted">

            <i class="bi bi-person-circle"></i>

            Administrator

        </div>


    </div>



    <!-- =========================================
         STATISTICS CARDS
    ========================================= -->

    <div class="row g-4 mb-4">


        <!-- TOTAL USERS -->

        <div class="col-lg-3 col-md-6">


            <div class="card stat-card">


                <div class="card-body d-flex justify-content-between align-items-center">


                    <div>


                        <small class="text-muted">

                            Total Users

                        </small>


                        <h3 class="mb-0">

                            <?php echo $total_users; ?>

                        </h3>


                    </div>


                    <div class="stat-icon bg-primary-subtle text-primary">

                        <i class="bi bi-people"></i>

                    </div>


                </div>


            </div>


        </div>



        <!-- TOTAL CABS -->

        <div class="col-lg-3 col-md-6">


            <div class="card stat-card">


                <div class="card-body d-flex justify-content-between align-items-center">


                    <div>


                        <small class="text-muted">

                            Total Cabs

                        </small>


                        <h3 class="mb-0">

                            <?php echo $total_cabs; ?>

                        </h3>


                    </div>


                    <div class="stat-icon bg-warning-subtle text-warning">

                        <i class="bi bi-taxi-front"></i>

                    </div>


                </div>


            </div>


        </div>



        <!-- TOTAL BOOKINGS -->

        <div class="col-lg-3 col-md-6">


            <div class="card stat-card">


                <div class="card-body d-flex justify-content-between align-items-center">


                    <div>


                        <small class="text-muted">

                            Total Bookings

                        </small>


                        <h3 class="mb-0">

                            <?php echo $total_bookings; ?>

                        </h3>


                    </div>


                    <div class="stat-icon bg-success-subtle text-success">

                        <i class="bi bi-calendar-check"></i>

                    </div>


                </div>


            </div>


        </div>



        <!-- PENDING BOOKINGS -->

        <div class="col-lg-3 col-md-6">


            <div class="card stat-card">


                <div class="card-body d-flex justify-content-between align-items-center">


                    <div>


                        <small class="text-muted">

                            Pending Bookings

                        </small>


                        <h3 class="mb-0">

                            <?php echo $pending_bookings; ?>

                        </h3>


                    </div>


                    <div class="stat-icon bg-danger-subtle text-danger">

                        <i class="bi bi-clock-history"></i>

                    </div>


                </div>


            </div>


        </div>


    </div>



    <!-- =========================================
         RECENT BOOKINGS
    ========================================= -->

    <div class="table-box">


        <div class="d-flex justify-content-between align-items-center mb-3">


            <h5 class="mb-0">

                Recent Bookings

            </h5>


            <a
                href="bookings/index.php"
                class="btn btn-sm btn-dark"
            >

                View All

            </a>


        </div>


        <div class="table-responsive">


            <table class="table table-hover align-middle">


                <thead class="table-light">


                    <tr>

                        <th>ID</th>

                        <th>User</th>

                        <th>Cab</th>

                        <th>Pickup</th>

                        <th>Drop</th>

                        <th>Price</th>

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
                                        $booking["user_name"]
                                    );

                                    ?>

                                </td>


                                <td>


                                    <?php

                                    echo htmlspecialchars(
                                        $booking["cab_type"]
                                    );

                                    ?>


                                    <br>


                                    <small class="text-muted">

                                        <?php

                                        echo htmlspecialchars(
                                            $booking["cab_number"]
                                        );

                                        ?>

                                    </small>


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

                                    ₹<?php

                                    echo number_format(
                                        $booking["fare"],
                                        2
                                    );

                                    ?>

                                </td>


                                <td>


                                    <?php

                                    $status_class =
                                        strtolower(
                                            $booking["status"]
                                        );

                                    ?>


                                    <span
                                        class="status <?php echo $status_class; ?>"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $booking["status"]
                                        );

                                        ?>

                                    </span>


                                </td>


                            </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <tr>


                            <td
                                colspan="7"
                                class="text-center text-muted py-4"
                            >

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