<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";


// ========================================
// GET ALL CABS
// ========================================

$cabs = $conn->query(

    "SELECT *
     FROM cabs
     ORDER BY id DESC"

);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Cabs - CabGo</title>


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


        /* SIDEBAR */

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


        /* MAIN */

        .main-content {

            margin-left: 250px;
            padding: 30px;

        }


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


        .table-box {

            background: white;

            padding: 25px;

            border-radius: 12px;

            box-shadow:
                0 3px 15px rgba(0,0,0,0.06);

        }


        /* STATUS */

        .status-available {

            background: #d1e7dd;
            color: #0f5132;

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: bold;

        }


        .status-unavailable {

            background: #f8d7da;
            color: #842029;

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: bold;

        }


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


<!-- SIDEBAR -->

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


    <a href="../bookings/index.php">

        <i class="bi bi-calendar-check"></i>

        <span>Bookings</span>

    </a>


    <a
        href="index.php"
        class="active"
    >

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



<!-- MAIN CONTENT -->

<div class="main-content">


    <!-- TOPBAR -->

    <div class="topbar">


        <div>

            <h4 class="mb-0">

                Manage Cabs

            </h4>


            <small class="text-muted">

                Add and manage available cabs

            </small>

        </div>


        <a
            href="add.php"
            class="btn btn-warning"
        >

            <i class="bi bi-plus-circle"></i>

            Add New Cab

        </a>


    </div>



    <!-- MESSAGES -->

    <?php if (isset($_GET["message"])): ?>


        <?php if ($_GET["message"] == "added"): ?>

            <div class="alert alert-success">

                Cab added successfully.

            </div>


        <?php elseif ($_GET["message"] == "updated"): ?>

            <div class="alert alert-success">

                Cab updated successfully.

            </div>


        <?php elseif ($_GET["message"] == "deleted"): ?>

            <div class="alert alert-success">

                Cab deleted successfully.

            </div>


        <?php elseif ($_GET["message"] == "error"): ?>

            <div class="alert alert-danger">

                Something went wrong.

            </div>


        <?php elseif ($_GET["message"] == "has_bookings"): ?>

            <div class="alert alert-warning">

                This cab cannot be deleted because it has active bookings.

            </div>


        <?php endif; ?>


    <?php endif; ?>



    <!-- TABLE -->

    <div class="table-box">


        <div class="table-responsive">


            <table class="table table-hover align-middle">


                <thead class="table-light">


                    <tr>

                        <th>ID</th>

                        <th>Cab Number</th>

                        <th>Cab Type</th>

                        <th>Price / KM</th>


                        <th>Status</th>

                        <th>Action</th>

                    </tr>


                </thead>


                <tbody>


                    <?php if ($cabs->num_rows > 0): ?>


                        <?php while ($cab = $cabs->fetch_assoc()): ?>


                            <tr>


                                <td>

                                    #<?php echo $cab["id"]; ?>

                                </td>


                                <td>

                                    <strong>

                                        <?php

                                        echo htmlspecialchars(
                                            $cab["cab_number"]
                                        );

                                        ?>

                                    </strong>

                                </td>


                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $cab["cab_type"]
                                    );

                                    ?>

                                </td>


                                

                                <td>

                                    ₹<?php

                                    echo number_format(
                                        $cab["fare_per_km"],
                                        2
                                    );

                                    ?>

                                </td>


                                <td>


                                    <span
                                        class="status-<?php
                                        echo strtolower(
                                            $cab["status"]
                                        );
                                        ?>"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $cab["status"]
                                        );

                                        ?>

                                    </span>


                                </td>


                                <td>


                                    <a
                                        href="edit.php?id=<?php echo $cab["id"]; ?>"
                                        class="btn btn-sm btn-primary"
                                    >

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    <a
                                        href="delete.php?id=<?php echo $cab["id"]; ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this cab?')"
                                    >

                                        <i class="bi bi-trash"></i>

                                    </a>


                                </td>


                            </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="7"
                                class="text-center text-muted py-4"
                            >

                                <i class="bi bi-taxi-front fs-3 d-block mb-2"></i>

                                No cabs found.

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