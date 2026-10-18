<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];


// ========================================
// CHECK BOOKING ID
// ========================================

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: my-bookings.php");
    exit();

}


$booking_id = intval($_GET["id"]);


// ========================================
// GET BOOKING DETAILS
// ========================================

$stmt = $conn->prepare(

    "SELECT

        bookings.*,

        cabs.cab_number,
        cabs.cab_type,
        cabs.driver_name,
        cabs.driver_phone,
        cabs.fare_per_km

    FROM bookings

    JOIN cabs
        ON bookings.cab_id = cabs.id

    WHERE bookings.id = ?
    AND bookings.user_id = ?"

);


$stmt->bind_param(
    "ii",
    $booking_id,
    $user_id
);


$stmt->execute();

$result = $stmt->get_result();


// ========================================
// BOOKING NOT FOUND
// ========================================

if ($result->num_rows !== 1) {

    header("Location: my-bookings.php");
    exit();

}


$booking = $result->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Booking Details - CabGo</title>


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


        /* =========================
           BOOKING CARD
        ========================= */

        .details-card {

            background: white;

            border-radius: 12px;

            padding: 30px;

            box-shadow:
                0 3px 15px rgba(0,0,0,0.06);

        }


        .section-title {

            font-size: 18px;

            font-weight: bold;

            margin-bottom: 20px;

            color: #0b1c2c;

            border-bottom: 2px solid #eee;

            padding-bottom: 10px;

        }


        .detail-item {

            margin-bottom: 20px;

        }


        .detail-label {

            font-size: 13px;

            color: #6c757d;

            margin-bottom: 5px;

        }


        .detail-value {

            font-size: 16px;

            font-weight: 600;

            color: #212529;

        }


        /* CAB BOX */

        .cab-info {

            background: #fff8db;

            border-left: 5px solid #ffc107;

            padding: 20px;

            border-radius: 8px;

        }


        .cab-icon {

            font-size: 50px;

            color: #ffc107;

        }


        /* STATUS */

        .status-badge {

            display: inline-block;

            padding: 8px 16px;

            border-radius: 20px;

            font-size: 14px;

            font-weight: bold;

        }


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


        /* FARE */

        .fare-box {

            background: #0b1c2c;

            color: white;

            padding: 25px;

            border-radius: 10px;

            text-align: center;

        }


        .fare-box h2 {

            color: #ffc107;

            margin-top: 10px;

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

                Booking Details

            </h4>

            <small class="text-muted">

                Complete information about your ride

            </small>

        </div>


        <a
            href="my-bookings.php"
            class="btn btn-outline-secondary"
        >

            <i class="bi bi-arrow-left"></i>

            Back to Bookings

        </a>


    </div>



    <!-- =========================
         DETAILS CARD
    ========================= -->

    <div class="details-card">


        <div class="d-flex justify-content-between align-items-center mb-4">


            <div>

                <h4>

                    Booking #<?php echo $booking["id"]; ?>

                </h4>


                <small class="text-muted">

                    Created on

                    <?php

                    echo date(
                        "d M Y, h:i A",
                        strtotime($booking["created_at"])
                    );

                    ?>

                </small>

            </div>


            <?php

            $status = strtolower($booking["status"]);

            ?>


            <span
                class="status-badge status-<?php echo $status; ?>"
            >

                <?php
                echo htmlspecialchars($booking["status"]);
                ?>

            </span>


        </div>



        <div class="row g-4">


            <!-- =========================
                 LEFT SIDE
            ========================= -->

            <div class="col-lg-8">


                <!-- CAB DETAILS -->

                <div class="section-title">

                    <i class="bi bi-taxi-front"></i>

                    Cab Details

                </div>


                <div class="cab-info mb-4">


                    <div class="row align-items-center">


                        <div class="col-md-2 text-center">

                            <div class="cab-icon">

                                <i class="bi bi-taxi-front-fill"></i>

                            </div>

                        </div>


                        <div class="col-md-10">


                            <h4>

                                <?php
                                echo htmlspecialchars(
                                    $booking["cab_type"]
                                );
                                ?>

                            </h4>


                            <p class="mb-1">

                                <strong>Cab Number:</strong>

                                <?php
                                echo htmlspecialchars(
                                    $booking["cab_number"]
                                );
                                ?>

                            </p>


                            <p class="mb-1">
                                <strong>Driver:</strong>
                                <?php echo htmlspecialchars($booking["driver_name"]); ?>
                            </p>

                            <p class="mb-1">
                                <strong>Driver Phone:</strong>
                                <?php echo htmlspecialchars($booking["driver_phone"]); ?>
                            </p>

                            <p class="mb-1">
                                <strong>Fare per KM:</strong>
                                ₹<?php echo number_format($booking["fare_per_km"], 2); ?>
                            </p>


                            <p class="mb-0">

                                <strong>Total Fare:</strong>

                                ₹<?php
                                echo htmlspecialchars(
                                    $booking["fare"]
                                );
                                ?>

                            </p>


                        </div>


                    </div>


                </div>



                <!-- TRIP DETAILS -->

                <div class="section-title">

                    <i class="bi bi-geo-alt"></i>

                    Trip Details

                </div>


                <div class="row">


                    <!-- PICKUP -->

                    <div class="col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">

                                PICKUP LOCATION

                            </div>


                            <div class="detail-value">

                                <i class="bi bi-geo-alt-fill text-success"></i>

                                <?php
                                echo htmlspecialchars(
                                    $booking["pickup_location"]
                                );
                                ?>

                            </div>

                        </div>

                    </div>



                    <!-- DROP -->

                    <div class="col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">

                                DROP LOCATION

                            </div>


                            <div class="detail-value">

                                <i class="bi bi-geo-alt-fill text-danger"></i>

                                <?php
                                echo htmlspecialchars(
                                    $booking["drop_location"]
                                );
                                ?>

                            </div>

                        </div>

                    </div>



                    <!-- DATE -->

                    <div class="col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">

                                BOOKING DATE

                            </div>


                            <div class="detail-value">

                                <i class="bi bi-calendar"></i>

                                <?php

                                echo date(
                                    "d F Y",
                                    strtotime(
                                        $booking["booking_date"]
                                    )
                                );

                                ?>

                            </div>

                        </div>

                    </div>



                    <!-- TIME -->

                    <div class="col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">

                                BOOKING TIME

                            </div>


                            <div class="detail-value">

                                <i class="bi bi-clock"></i>

                                <?php

                                echo date(
                                    "h:i A",
                                    strtotime(
                                        $booking["booking_time"]
                                    )
                                );

                                ?>

                            </div>

                        </div>

                    </div>



                    <!-- DISTANCE -->

                    <div class="col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">

                                DISTANCE

                            </div>


                            <div class="detail-value">

                                <i class="bi bi-signpost"></i>

                                <?php
                                echo $booking["distance"];
                                ?>

                                KM

                            </div>

                        </div>

                    </div>



                    <!-- PRICE RATE -->

                    <div class="col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">

                                PRICE PER KM

                            </div>


                            <div class="detail-value">

                                ₹<?php
                                echo $booking["fare_per_km"];
                                ?>

                                / KM

                            </div>

                        </div>

                    </div>


                </div>


            </div>



            <!-- =========================
                 RIGHT SIDE
            ========================= -->

            <div class="col-lg-4">


                <!-- TOTAL PRICE BOX -->

                <div class="fare-box">


                    <p class="mb-0">

                        Total Price

                    </p>


                    <h2>

                        ₹<?php

                        echo number_format(
                            $booking["fare"],
                            2
                        );

                        ?>

                    </h2>


                    <small>

                        <?php
                        echo $booking["distance"];
                        ?>

                        KM Ride

                    </small>


                </div>



                <!-- CANCEL BUTTON -->

                <?php

                if (

                    $booking["status"] == "Pending"

                    ||

                    $booking["status"] == "Confirmed"

                ):

                ?>


                    <div class="mt-3 d-grid">


                        <a

                            href="cancel-booking.php?id=<?php
                            echo $booking["id"];
                            ?>"

                            class="btn btn-outline-danger"

                            onclick="
                            return confirm(
                                'Are you sure you want to cancel this booking?'
                            );
                            "

                        >

                            <i class="bi bi-x-circle"></i>

                            Cancel Booking

                        </a>


                    </div>


                <?php endif; ?>


            </div>


        </div>


    </div>


</div>


</body>

</html>