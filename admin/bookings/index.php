<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";


// =========================================
// GET ALL BOOKINGS WITH USER AND CAB DATA
// =========================================

$sql = "

    SELECT

        bookings.*,

        users.name AS user_name,
        users.email AS user_email,

        cabs.cab_type,
        cabs.cab_number

    FROM bookings

    INNER JOIN users
        ON bookings.user_id = users.id

    INNER JOIN cabs
        ON bookings.cab_id = cabs.id

    ORDER BY bookings.id DESC

";


$bookings = $conn->query($sql);

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Bookings - CabGo</title>


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


        .topbar {

            background: white;

            padding: 20px 25px;

            border-radius: 10px;

            margin-bottom: 30px;

            box-shadow:
                0 2px 10px rgba(0,0,0,0.05);

        }


        .table-box {

            background: white;

            padding: 25px;

            border-radius: 12px;

            box-shadow:
                0 3px 15px rgba(0,0,0,0.06);

        }


        /* =========================
           STATUS BADGES
        ========================= */

        .status {

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: bold;

            display: inline-block;

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


        /* =========================
           RESPONSIVE
        ========================= */

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


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">


    <div class="logo">

        <i class="bi bi-taxi-front-fill"></i>

        <span>CabGo</span>

    </div>


    <a href="../dashboard.php">

        <i class="bi bi-grid"></i>

        <span>Dashboard</span>

    </a>


    <a href="../users/index.php">

        <i class="bi bi-people"></i>

        <span>Users</span>

    </a>


    <a
        href="index.php"
        class="active"
    >

        <i class="bi bi-calendar-check"></i>

        <span>Bookings</span>

    </a>


    <a href="../cabs/index.php">

        <i class="bi bi-taxi-front"></i>

        <span>Manage Cabs</span>

    </a>


    <hr>


    <a
        href="../../logout.php"
        class="text-danger"
    >

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

        <h4 class="mb-1">

            Manage Bookings

        </h4>


        <small class="text-muted">

            View and manage all cab bookings

        </small>

    </div>



    <!-- =========================
         SUCCESS / ERROR MESSAGE
    ========================= -->

    <?php if (isset($_GET["message"])): ?>


        <?php if ($_GET["message"] == "updated"): ?>


            <div class="alert alert-success">

                Booking status updated successfully.

            </div>


        <?php elseif ($_GET["message"] == "deleted"): ?>


            <div class="alert alert-success">

                Booking deleted successfully.

            </div>


        <?php elseif ($_GET["message"] == "error"): ?>


            <div class="alert alert-danger">

                Something went wrong.

            </div>


        <?php endif; ?>


    <?php endif; ?>



    <!-- =========================
         BOOKINGS TABLE
    ========================= -->

    <div class="table-box">


        <div class="table-responsive">


            <table class="table table-hover align-middle">


                <thead class="table-light">


                    <tr>

                        <th>ID</th>

                        <th>User</th>

                        <th>Cab</th>

                        <th>Pickup</th>

                        <th>Drop</th>

                        <th>Date</th>

                        <th>Price</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>


                </thead>


                <tbody>


                    <?php if ($bookings->num_rows > 0): ?>


                        <?php while ($booking = $bookings->fetch_assoc()): ?>


                            <tr>


                                <!-- BOOKING ID -->

                                <td>

                                    #<?php echo $booking["id"]; ?>

                                </td>



                                <!-- USER -->

                                <td>


                                    <strong>

                                        <?php

                                        echo htmlspecialchars(
                                            $booking["user_name"]
                                        );

                                        ?>

                                    </strong>


                                    <br>


                                    <small class="text-muted">

                                        <?php

                                        echo htmlspecialchars(
                                            $booking["user_email"]
                                        );

                                        ?>

                                    </small>


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



                                <!-- BOOKING DATE -->

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



                                <!-- PRICE -->

                                <td>
                                    ₹<?php echo number_format($booking["fare"], 2); ?>
                                </td>



                                <!-- STATUS -->

                                <td>


                                    <?php

                                    $status_class = strtolower(
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



                                <!-- ACTIONS -->

                                <td>


                                    <!-- VIEW -->

                                    <a
                                        href="view.php?id=<?php echo $booking["id"]; ?>"
                                        class="btn btn-sm btn-primary"
                                        title="View Booking"
                                    >

                                        <i class="bi bi-eye"></i>

                                    </a>



                                    <!-- UPDATE STATUS -->

                                    <a
                                        href="update-status.php?id=<?php echo $booking["id"]; ?>"
                                        class="btn btn-sm btn-warning"
                                        title="Update Status"
                                    >

                                        <i class="bi bi-pencil-square"></i>

                                    </a>



                                    <!-- DELETE -->

                                    <a
                                        href="delete.php?id=<?php echo $booking["id"]; ?>"
                                        class="btn btn-sm btn-danger"
                                        title="Delete Booking"

                                        onclick="
                                            return confirm(
                                                'Are you sure you want to delete this booking?'
                                            );
                                        "
                                    >

                                        <i class="bi bi-trash"></i>

                                    </a>


                                </td>


                            </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <tr>


                            <td
                                colspan="9"
                                class="text-center text-muted py-4"
                            >


                                <i
                                    class="bi bi-calendar-x fs-3 d-block mb-2"
                                ></i>


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