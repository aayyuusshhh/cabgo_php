<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];


// ========================================
// GET USER BOOKINGS
// ========================================

$stmt = $conn->prepare(

    "SELECT

        bookings.*,

        cabs.cab_number,
        cabs.cab_type

    FROM bookings

    JOIN cabs
        ON bookings.cab_id = cabs.id

    WHERE bookings.user_id = ?

    ORDER BY bookings.created_at DESC"

);


$stmt->bind_param(
    "i",
    $user_id
);


$stmt->execute();


$bookings = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>My Bookings - CabGo</title>


    <!-- Bootstrap CSS -->

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


        /* TABLE BOX */

        .booking-box {

            background: white;

            padding: 25px;

            border-radius: 12px;

            box-shadow:
                0 3px 15px rgba(0,0,0,0.06);

        }


        /* STATUS BADGES */

        .status-pending {

            background: #fff3cd;

            color: #856404;

        }


        .status-confirmed {

            background: #cfe2ff;

            color: #084298;

        }


        .status-completed {

            background: #d1e7dd;

            color: #0f5132;

        }


        .status-cancelled {

            background: #f8d7da;

            color: #842029;

        }


        .status-badge {

            padding: 7px 12px;

            border-radius: 20px;

            font-size: 13px;

            font-weight: 600;

        }


        /* EMPTY STATE */

        .empty-bookings {

            text-align: center;

            padding: 60px 20px;

        }


        .empty-bookings i {

            font-size: 70px;

            color: #ffc107;

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


    <a href="my-bookings.php"
        class="active">

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



<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main-content">


    <!-- TOPBAR -->

    <div class="topbar">

        <div>

            <h4 class="mb-0">

                My Bookings

            </h4>

            <small class="text-muted">

                View and manage your cab bookings

            </small>

        </div>


        <div>

            <a href="book-cab.php"
                class="btn btn-warning">

                <i class="bi bi-plus-circle"></i>

                Book New Cab

            </a>

        </div>


    </div>



    <!-- =========================
         BOOKINGS TABLE
    ========================= -->

    <div class="booking-box">


        <?php if ($bookings->num_rows > 0): ?>


            <div class="table-responsive">


                <table class="table table-hover align-middle">


                    <thead class="table-light">

                        <tr>

                            <th>Booking ID</th>

                            <th>Cab</th>

                            <th>Pickup</th>

                            <th>Drop</th>

                            <th>Date & Time</th>

                            <th>Distance</th>

                            <th>Fare</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php while ($booking = $bookings->fetch_assoc()): ?>


                        <tr>


                            <!-- BOOKING ID -->

                            <td>

                                <strong>

                                    #<?php
                                    echo $booking["id"];
                                    ?>

                                </strong>

                            </td>



                            <!-- CAB -->

                            <td>

                                <strong>

                                    <?php
                                    echo htmlspecialchars(
                                        $booking["cab_type"]
                                    );
                                    ?>

                                </strong>


                                <br>


                                <small class="text-muted">

                                    <?php
                                    echo htmlspecialchars(
                                        $booking["cab_number"]
                                    );
                                    ?>

                                </small>

                            </td>



                            <!-- PICKUP -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $booking["pickup_location"]
                                );
                                ?>

                            </td>



                            <!-- DROP -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $booking["drop_location"]
                                );
                                ?>

                            </td>



                            <!-- DATE TIME -->

                            <td>

                                <?php

                                echo date(
                                    "d M Y",
                                    strtotime(
                                        $booking["booking_date"]
                                    )
                                );

                                ?>

                                <br>


                                <small class="text-muted">

                                    <?php

                                    echo date(
                                        "h:i A",
                                        strtotime(
                                            $booking["booking_time"]
                                        )
                                    );

                                    ?>

                                </small>

                            </td>



                            <!-- DISTANCE -->

                            <td>

                                <?php
                                echo $booking["distance"];
                                ?>

                                KM

                            </td>



                            <!-- TOTAL PRICE -->

                            <td>

                                <strong>

                                    ₹<?php
                                    echo number_format(
                                        $booking["fare"],
                                        2
                                    );
                                    ?>

                                </strong>

                            </td>



                            <!-- STATUS -->

                            <td>


                                <?php

                                $status =
                                    strtolower(
                                        $booking["status"]
                                    );

                                ?>


                                <span
                                    class="
                                    status-badge
                                    status-<?php echo $status; ?>
                                    ">

                                    <?php
                                    echo htmlspecialchars(
                                        $booking["status"]
                                    );
                                    ?>

                                </span>


                            </td>



                            <!-- ACTION -->

                            <td>


                                <div class="d-flex gap-2">


                                    <!-- VIEW DETAILS -->

                                    <a
                                        href="booking-details.php?id=<?php
                                        echo $booking["id"];
                                        ?>"

                                        class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-eye"></i>

                                    </a>



                                    <!-- CANCEL BUTTON -->

                                    <?php

                                    if (

                                        $booking["status"] == "Pending"

                                        ||

                                        $booking["status"] == "Confirmed"

                                    ):

                                    ?>


                                        <a

                                            href="cancel-booking.php?id=<?php
                                            echo $booking["id"];
                                            ?>"

                                            class="btn btn-sm btn-outline-danger"

                                            onclick="
                                            return confirm(
                                                'Are you sure you want to cancel this booking?'
                                            );
                                            "

                                        >

                                            <i class="bi bi-x-circle"></i>

                                        </a>


                                    <?php endif; ?>


                                </div>


                            </td>


                        </tr>


                    <?php endwhile; ?>


                    </tbody>


                </table>


            </div>


        <?php else: ?>


            <!-- EMPTY STATE -->

            <div class="empty-bookings">


                <i class="bi bi-calendar-x"></i>


                <h4 class="mt-3">

                    No Bookings Yet

                </h4>


                <p class="text-muted">

                    You haven't booked any cab yet.

                </p>


                <a
                    href="book-cab.php"
                    class="btn btn-warning">

                    <i class="bi bi-taxi-front"></i>

                    Book Your First Cab

                </a>


            </div>


        <?php endif; ?>


    </div>


</div>


</body>

</html>