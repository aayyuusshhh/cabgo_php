<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";


// =========================================
// CHECK BOOKING ID
// =========================================

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: index.php");
    exit();

}

$booking_id = intval($_GET["id"]);


// =========================================
// GET COMPLETE BOOKING DETAILS
// =========================================

$sql = "

    SELECT

        bookings.*,

        users.name AS user_name,
        users.email AS user_email,
        users.phone AS user_phone,

        cabs.cab_type,
        cabs.cab_number,
        cabs.capacity,
        cabs.price_per_km

    FROM bookings

    INNER JOIN users
        ON bookings.user_id = users.id

    INNER JOIN cabs
        ON bookings.cab_id = cabs.id

    WHERE bookings.id = ?

";


$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $booking_id);

$stmt->execute();

$result = $stmt->get_result();


// =========================================
// CHECK BOOKING EXISTS
// =========================================

if ($result->num_rows !== 1) {

    header("Location: index.php");
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

    <title>View Booking - CabGo</title>


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
            background: #f4f6f9;
        }


        .details-card {

            border: none;

            border-radius: 15px;

            box-shadow:
                0 4px 20px rgba(0,0,0,0.08);

        }


        .section-title {

            border-bottom: 2px solid #ffc107;

            padding-bottom: 10px;

            margin-bottom: 20px;

            font-weight: bold;

        }


        .info-label {

            color: #6c757d;

            font-size: 13px;

        }


        .info-value {

            font-weight: 600;

            font-size: 16px;

        }


        .status {

            padding: 7px 15px;

            border-radius: 20px;

            font-size: 13px;

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

    </style>

</head>


<body>


<div class="container py-5">


    <!-- HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">


        <div>

            <h2>

                Booking #<?php echo $booking["id"]; ?>

            </h2>


            <p class="text-muted mb-0">

                Complete booking information

            </p>

        </div>


        <div>


            <a
                href="update-status.php?id=<?php echo $booking["id"]; ?>"
                class="btn btn-warning"
            >

                <i class="bi bi-pencil-square"></i>

                Update Status

            </a>


            <a
                href="index.php"
                class="btn btn-secondary"
            >

                Back

            </a>


        </div>


    </div>



    <div class="card details-card">


        <div class="card-body p-4">


            <!-- STATUS -->

            <?php

            $status_class = strtolower($booking["status"]);

            ?>


            <div class="mb-4">


                <span class="status <?php echo $status_class; ?>">

                    <?php

                    echo htmlspecialchars(
                        $booking["status"]
                    );

                    ?>

                </span>


            </div>



            <div class="row">


                <!-- USER INFORMATION -->

                <div class="col-md-6 mb-5">


                    <h5 class="section-title">

                        <i class="bi bi-person"></i>

                        User Information

                    </h5>


                    <div class="mb-3">

                        <div class="info-label">
                            Name
                        </div>

                        <div class="info-value">

                            <?php
                            echo htmlspecialchars($booking["user_name"]);
                            ?>

                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="info-label">
                            Email
                        </div>

                        <div class="info-value">

                            <?php
                            echo htmlspecialchars($booking["user_email"]);
                            ?>

                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="info-label">
                            Phone
                        </div>

                        <div class="info-value">

                            <?php
                            echo htmlspecialchars($booking["user_phone"]);
                            ?>

                        </div>

                    </div>


                </div>



                <!-- CAB INFORMATION -->

                <div class="col-md-6 mb-5">


                    <h5 class="section-title">

                        <i class="bi bi-taxi-front"></i>

                        Cab Information

                    </h5>


                    <div class="mb-3">

                        <div class="info-label">
                            Cab Type
                        </div>

                        <div class="info-value">

                            <?php
                            echo htmlspecialchars($booking["cab_type"]);
                            ?>

                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="info-label">
                            Cab Number
                        </div>

                        <div class="info-value">

                            <?php
                            echo htmlspecialchars($booking["cab_number"]);
                            ?>

                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="info-label">
                            Capacity
                        </div>

                        <div class="info-value">

                            <?php
                            echo htmlspecialchars($booking["capacity"]);
                            ?>

                            Passengers

                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="info-label">
                            Price Per KM
                        </div>

                        <div class="info-value">

                            ₹<?php
                            echo htmlspecialchars($booking["price_per_km"]);
                            ?>

                        </div>

                    </div>


                </div>



                <!-- TRIP INFORMATION -->

                <div class="col-md-6 mb-4">


                    <h5 class="section-title">

                        <i class="bi bi-geo-alt"></i>

                        Trip Information

                    </h5>


                    <div class="mb-3">

                        <div class="info-label">
                            Pickup Location
                        </div>

                        <div class="info-value">

                            <?php
                            echo htmlspecialchars(
                                $booking["pickup_location"]
                            );
                            ?>

                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="info-label">
                            Drop Location
                        </div>

                        <div class="info-value">

                            <?php
                            echo htmlspecialchars(
                                $booking["drop_location"]
                            );
                            ?>

                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="info-label">
                            Distance
                        </div>

                        <div class="info-value">

                            <?php
                            echo htmlspecialchars(
                                $booking["distance"]
                            );
                            ?>

                            KM

                        </div>

                    </div>


                </div>



                <!-- BOOKING INFORMATION -->

                <div class="col-md-6 mb-4">


                    <h5 class="section-title">

                        <i class="bi bi-receipt"></i>

                        Booking Information

                    </h5>


                    <div class="mb-3">

                        <div class="info-label">
                            Booking Date
                        </div>

                        <div class="info-value">

                            <?php

                            echo date(
                                "d M Y",
                                strtotime(
                                    $booking["booking_date"]
                                )
                            );

                            ?>

                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="info-label">
                            Booking Time
                        </div>

                        <div class="info-value">

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


                    <div class="mb-3">

                        <div class="info-label">
                            Total Price
                        </div>

                        <div class="info-value fs-4 text-success">

                            ₹<?php

                            echo number_format(
                                $booking["fare"],
                                2
                            );

                            ?>

                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="info-label">
                            Booking Created At
                        </div>

                        <div class="info-value">

                            <?php

                            echo date(
                                "d M Y h:i A",
                                strtotime(
                                    $booking["created_at"]
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


</body>

</html>