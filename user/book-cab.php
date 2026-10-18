<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];


// =========================================
// GET USER DETAILS
// =========================================

$stmt = $conn->prepare(
    "SELECT name, email FROM users WHERE id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();


// =========================================
// CHECK IF BOOKING A SPECIFIC CAB
// =========================================

$booking_mode = isset($_GET["cab_id"]) && is_numeric($_GET["cab_id"]);

$selected_cab = null;
$booking_message = "";


if ($booking_mode) {

    $cab_id = intval($_GET["cab_id"]);


    // =====================================
    // GET CAB DETAILS
    // =====================================

    $stmt = $conn->prepare(
        "SELECT * FROM cabs WHERE id = ?"
    );

    $stmt->bind_param("i", $cab_id);
    $stmt->execute();

    $cab_result = $stmt->get_result();


    if ($cab_result->num_rows === 1) {

        $selected_cab = $cab_result->fetch_assoc();

    } else {

        header("Location: book-cab.php");
        exit();

    }


    // =====================================
    // PROCESS BOOKING
    // =====================================

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $pickup = trim($_POST["pickup_location"]);
        $drop = trim($_POST["drop_location"]);
        $booking_date = $_POST["booking_date"];
        $booking_time = $_POST["booking_time"];
        $distance = floatval($_POST["distance"]);


        // =================================
        // VALIDATION
        // =================================

        if (
            empty($pickup) ||
            empty($drop) ||
            empty($booking_date) ||
            empty($booking_time) ||
            $distance <= 0
        ) {

            $booking_message = "
                <div class='alert alert-danger'>
                    Please fill all fields correctly.
                </div>
            ";

        } else {

            // ==============================
            // CALCULATE TOTAL PRICE
            // ==============================

            $fare =
                $distance * $selected_cab["fare_per_km"];


            // ==============================
            // INSERT BOOKING
            // ==============================

            $insert_stmt = $conn->prepare(
                "INSERT INTO bookings
                (
                    user_id,
                    cab_id,
                    pickup_location,
                    drop_location,
                    booking_date,
                    booking_time,
                    distance,
                    fare,
                    status
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')"
            );


            $insert_stmt->bind_param(
                "iissssdd",
                $user_id,
                $cab_id,
                $pickup,
                $drop,
                $booking_date,
                $booking_time,
                $distance,
                $fare
            );


            if ($insert_stmt->execute()) {

                header(
                    "Location: my-bookings.php?new=success"
                );

                exit();

            } else {

                $booking_message = "
                    <div class='alert alert-danger'>
                        Failed to create booking.
                        Please try again.
                    </div>
                ";
            }
        }
    }


} else {


    // =========================================
    // GET AVAILABLE CABS
    // =========================================

    $cabs = $conn->query(
        "SELECT * FROM cabs
         WHERE status = 'Available'
         ORDER BY id DESC"
    );

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

    <title>Book Cab - CabGo</title>


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

        /* =========================================
           GLOBAL
        ========================================= */

        body {

            background: #f4f6f9;

            font-family: Arial, sans-serif;

        }


        /* =========================================
           NAVBAR
        ========================================= */

        .navbar {

            background: #0b1c2c;

        }


        .navbar-brand {

            color: #ffc107 !important;

            font-size: 26px;

            font-weight: bold;

        }


        .navbar-brand i {

            margin-right: 5px;

        }


        /* =========================================
           WELCOME SECTION
        ========================================= */

        .welcome-section {

            background: linear-gradient(
                135deg,
                #0b1c2c,
                #163b5c
            );

            color: white;

            padding: 50px 0;

        }


        .welcome-section h1 {

            font-weight: bold;

        }


        /* =========================================
           CAB CARD
        ========================================= */

        .cab-card {

            border: none;

            border-radius: 15px;

            overflow: hidden;

            height: 100%;

            box-shadow:
                0 5px 20px
                rgba(0,0,0,0.08);

            transition: 0.3s;

        }


        .cab-card:hover {

            transform: translateY(-7px);

            box-shadow:
                0 12px 30px
                rgba(0,0,0,0.15);

        }


        .cab-icon {

            height: 140px;

            background: #0b1c2c;

            color: #ffc107;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 70px;

        }


        .price {

            color: #198754;

            font-size: 22px;

            font-weight: bold;

        }


        .cab-details {

            color: #666;

            font-size: 14px;

        }


        /* =========================================
           BOOK BUTTON
        ========================================= */

        .btn-book {

            background: #ffc107;

            color: #000;

            border: none;

            font-weight: bold;

            width: 100%;

            padding: 10px;

            transition: 0.3s;

        }


        .btn-book:hover {

            background: #e0a800;

            color: #000;

        }


        /* =========================================
           SECTION TITLE
        ========================================= */

        .section-title {

            font-weight: bold;

            color: #0b1c2c;

        }


        /* =========================================
           EMPTY CAB MESSAGE
        ========================================= */

        .empty-box {

            background: white;

            padding: 50px;

            border-radius: 15px;

            text-align: center;

            box-shadow:
                0 5px 20px
                rgba(0,0,0,0.08);

        }

    </style>

</head>


<body>


<!-- =========================================
     NAVBAR
========================================= -->

<nav class="navbar navbar-expand-lg navbar-dark">

    <div class="container">


        <!-- LOGO -->

        <a
            class="navbar-brand"
            href="dashboard.php"
        >

            <i class="bi bi-taxi-front-fill"></i>

            CabGo

        </a>


        <!-- MOBILE BUTTON -->

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <!-- NAVIGATION -->

        <div
            class="collapse navbar-collapse"
            id="navbarMenu"
        >

            <ul class="navbar-nav ms-auto align-items-lg-center">


                <!-- DASHBOARD -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="dashboard.php"
                    >

                        <i class="bi bi-grid"></i>

                        Dashboard

                    </a>

                </li>


                <!-- MY BOOKINGS -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="my-bookings.php"
                    >

                        <i class="bi bi-calendar-check"></i>

                        My Bookings

                    </a>

                </li>


                <!-- PROFILE -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="profile.php"
                    >

                        <i class="bi bi-person"></i>

                        Profile

                    </a>

                </li>


                <!-- LOGOUT -->

                <li class="nav-item ms-lg-3">

                    <a
                        href="../logout.php"
                        class="btn btn-warning"
                    >

                        <i class="bi bi-box-arrow-right"></i>

                        Logout

                    </a>

                </li>


            </ul>

        </div>

    </div>

</nav>



<!-- =========================================
     WELCOME SECTION
========================================= -->

<section class="welcome-section">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-md-8">

                <h1>

                    Welcome,

                    <?php

                    echo htmlspecialchars(
                        $_SESSION["name"]
                    );

                    ?>!

                </h1>


                <p class="mb-0">

                    Find the perfect cab for your journey.

                    Book quickly, easily and securely with CabGo.

                </p>

            </div>


            <div class="col-md-4 text-md-end mt-3 mt-md-0">

                <i
                    class="bi bi-taxi-front-fill"
                    style="font-size: 80px; color: #ffc107;"
                ></i>

            </div>

        </div>

    </div>

</section>



<!-- =========================================
     BOOKING FORM
========================================= -->

<?php if ($booking_mode && $selected_cab): ?>


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow border-0">


                <!-- CARD HEADER -->

                <div class="card-header bg-dark text-white">

                    <h4 class="mb-0">

                        Book Your Ride

                    </h4>

                </div>


                <div class="card-body p-4">


                    <?php echo $booking_message; ?>


                    <!-- CAB DETAILS -->

                    <div class="alert alert-info mb-4">

                        <div class="row">

                            <div class="col-md-6">

                                <p>

                                    <strong>
                                        Cab Type:
                                    </strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $selected_cab["cab_type"]
                                    );

                                    ?>

                                </p>


                                <p>

                                    <strong>
                                        Cab Number:
                                    </strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $selected_cab["cab_number"]
                                    );

                                    ?>

                                </p>

                            </div>


                            <div class="col-md-6">

                                <p>

                                    <strong>
                                        Driver:
                                    </strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $selected_cab["driver_name"]
                                    );

                                    ?>

                                </p>


                                <p>

                                    <strong>
                                        Rate:
                                    </strong>

                                    ₹<?php

                                    echo number_format(
                                        $selected_cab["fare_per_km"],
                                        2
                                    );

                                    ?>/km

                                </p>

                            </div>

                        </div>

                    </div>



                    <!-- BOOKING FORM -->

                    <form method="POST">


                        <!-- PICKUP -->

                        <div class="mb-3">

                            <label class="form-label">

                                Pickup Location

                            </label>

                            <input
                                type="text"
                                name="pickup_location"
                                class="form-control"
                                placeholder="Enter pickup location"
                                required
                            >

                        </div>


                        <!-- DROP -->

                        <div class="mb-3">

                            <label class="form-label">

                                Drop Location

                            </label>

                            <input
                                type="text"
                                name="drop_location"
                                class="form-control"
                                placeholder="Enter drop location"
                                required
                            >

                        </div>


                        <!-- DATE -->

                        <div class="mb-3">

                            <label class="form-label">

                                Date

                            </label>

                            <input
                                type="date"
                                name="booking_date"
                                class="form-control"
                                min="<?php echo date('Y-m-d'); ?>"
                                required
                            >

                        </div>


                        <!-- TIME -->

                        <div class="mb-3">

                            <label class="form-label">

                                Time

                            </label>

                            <input
                                type="time"
                                name="booking_time"
                                class="form-control"
                                required
                            >

                        </div>


                        <!-- DISTANCE -->

                        <div class="mb-3">

                            <label class="form-label">

                                Distance (KM)

                            </label>

                            <input
                                type="number"
                                name="distance"
                                class="form-control"
                                step="0.1"
                                min="0.1"
                                placeholder="Enter distance in kilometers"
                                required
                            >

                        </div>


                        <!-- ESTIMATED FARE -->

                        <div
                            class="alert alert-warning"
                            id="fareAlert"
                            style="display:none;"
                        >

                            <strong>
                                Estimated Fare:
                            </strong>

                            ₹<span id="estimatedFare">
                                0.00
                            </span>

                        </div>


                        <!-- BUTTONS -->

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-warning flex-grow-1"
                            >

                                <i class="bi bi-check-circle"></i>

                                Confirm Booking

                            </button>


                            <a
                                href="book-cab.php"
                                class="btn btn-secondary"
                            >

                                <i class="bi bi-arrow-left"></i>

                                Back

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>



<script>

    const distanceInput =
        document.querySelector(
            'input[name="distance"]'
        );


    const fareAlert =
        document.getElementById(
            'fareAlert'
        );


    const estimatedFare =
        document.getElementById(
            'estimatedFare'
        );


    const pricePerKm =
        <?php echo $selected_cab["fare_per_km"]; ?>;


    distanceInput.addEventListener(
        'input',
        function () {

            if (this.value > 0) {

                const fare =
                    (
                        parseFloat(this.value)
                        * pricePerKm
                    ).toFixed(2);


                estimatedFare.textContent =
                    fare;


                fareAlert.style.display =
                    'block';

            } else {

                fareAlert.style.display =
                    'none';

            }

        }
    );

</script>


<?php endif; ?>



<!-- =========================================
     AVAILABLE CABS
========================================= -->

<?php if (!$booking_mode): ?>


<div class="container py-5">


    <!-- TITLE -->

    <div class="text-center mb-5">

        <h2 class="section-title">

            Available Cabs

        </h2>


        <p class="text-muted">

            Choose a cab that suits your journey.

        </p>

    </div>



    <div class="row g-4">


        <?php if ($cabs && $cabs->num_rows > 0): ?>


            <?php while ($cab = $cabs->fetch_assoc()): ?>


                <div class="col-lg-4 col-md-6">


                    <div class="card cab-card">


                        <!-- CAB ICON -->

                        <div class="cab-icon">

                            <i class="bi bi-taxi-front-fill"></i>

                        </div>


                        <!-- CARD BODY -->

                        <div class="card-body p-4">


                            <!-- CAB TYPE -->

                            <h4 class="mb-3">

                                <?php

                                echo htmlspecialchars(
                                    $cab["cab_type"]
                                );

                                ?>

                            </h4>


                            <!-- CAB NUMBER -->

                            <div class="cab-details mb-2">

                                <i class="bi bi-car-front-fill"></i>

                                <strong>
                                    Cab Number:
                                </strong>

                                <?php

                                echo htmlspecialchars(
                                    $cab["cab_number"]
                                );

                                ?>

                            </div>


                            <!-- DRIVER -->

                            <div class="cab-details mb-2">

                                <i class="bi bi-person-fill"></i>

                                <strong>
                                    Driver:
                                </strong>

                                <?php

                                echo htmlspecialchars(
                                    $cab["driver_name"]
                                );

                                ?>

                            </div>


                            <!-- DRIVER PHONE -->

                            <div class="cab-details mb-2">

                                <i class="bi bi-telephone-fill"></i>

                                <strong>
                                    Phone:
                                </strong>

                                <?php

                                echo htmlspecialchars(
                                    $cab["driver_phone"]
                                );

                                ?>

                            </div>


                            <!-- PRICE -->

                            <div class="mt-3 mb-4">

                                <span class="text-muted">

                                    Fare per KM

                                </span>


                                <div class="price">

                                    ₹<?php

                                    echo number_format(
                                        $cab["fare_per_km"],
                                        2
                                    );

                                    ?>

                                </div>

                            </div>


                            <!-- BOOK NOW -->

                            <a
                                href="book-cab.php?cab_id=<?php echo $cab["id"]; ?>"
                                class="btn btn-book"
                            >

                                <i class="bi bi-calendar-plus"></i>

                                Book Now

                            </a>

                        </div>

                    </div>

                </div>


            <?php endwhile; ?>


        <?php else: ?>


            <!-- NO CABS -->

            <div class="col-12">

                <div class="empty-box">


                    <i
                        class="bi bi-taxi-front"
                        style="font-size: 60px; color: #ffc107;"
                    ></i>


                    <h4 class="mt-3">

                        No Cabs Available

                    </h4>


                    <p class="text-muted">

                        Currently there are no cabs available.

                        Please check again later.

                    </p>

                </div>

            </div>


        <?php endif; ?>


    </div>

</div>


<?php endif; ?>



<!-- =========================================
     FOOTER
========================================= -->

<footer
    class="text-center py-4"
    style="background: #0b1c2c; color: white;"
>

    <p class="mb-0">

        © <?php echo date("Y"); ?>

        CabGo. All Rights Reserved.

    </p>

</footer>



<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>