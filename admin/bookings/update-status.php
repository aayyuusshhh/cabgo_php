<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";

$message = "";


// =========================================
// CHECK BOOKING ID
// =========================================

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: index.php");
    exit();

}

$booking_id = intval($_GET["id"]);


// =========================================
// GET BOOKING
// =========================================

$stmt = $conn->prepare(

    "SELECT * FROM bookings WHERE id = ?"

);

$stmt->bind_param(
    "i",
    $booking_id
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    header("Location: index.php");
    exit();

}

$booking = $result->fetch_assoc();


// =========================================
// UPDATE STATUS
// =========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $status = $_POST["status"];


    // VALID STATUSES

    $allowed_statuses = [

        "Pending",
        "Confirmed",
        "Completed",
        "Cancelled"

    ];


    // SECURITY VALIDATION

    if (!in_array($status, $allowed_statuses)) {


        $message = "

            <div class='alert alert-danger'>

                Invalid booking status.

            </div>

        ";


    } else {


        // UPDATE DATABASE

        $update = $conn->prepare(

            "UPDATE bookings
             SET status = ?
             WHERE id = ?"

        );


        $update->bind_param(

            "si",

            $status,
            $booking_id

        );


        if ($update->execute()) {


            header(
                "Location: index.php?message=updated"
            );

            exit();


        } else {


            $message = "

                <div class='alert alert-danger'>

                    Failed to update booking status.

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

    <title>Update Booking Status - CabGo</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


</head>


<body class="bg-light">


<div class="container py-5">


    <div class="row justify-content-center">


        <div class="col-md-6">


            <div class="card shadow border-0">


                <div class="card-header bg-dark text-white">


                    <h4 class="mb-0">

                        Update Booking Status

                    </h4>


                </div>



                <div class="card-body p-4">


                    <?php echo $message; ?>


                    <!-- CURRENT INFORMATION -->

                    <div class="alert alert-light border">


                        <strong>

                            Booking ID:

                        </strong>

                        #<?php echo $booking["id"]; ?>


                        <br>


                        <strong>

                            Current Status:

                        </strong>

                        <?php

                        echo htmlspecialchars(
                            $booking["status"]
                        );

                        ?>


                    </div>



                    <!-- FORM -->

                    <form method="POST">


                        <div class="mb-4">


                            <label class="form-label">

                                Select Booking Status

                            </label>


                            <select
                                name="status"
                                class="form-select"
                                required
                            >


                                <?php

                                $statuses = [

                                    "Pending",
                                    "Confirmed",
                                    "Completed",
                                    "Cancelled"

                                ];


                                foreach ($statuses as $status):

                                ?>


                                    <option

                                        value="<?php
                                        echo $status;
                                        ?>"

                                        <?php

                                        if (
                                            $booking["status"] == $status
                                        ) {

                                            echo "selected";

                                        }

                                        ?>

                                    >

                                        <?php
                                        echo $status;
                                        ?>

                                    </option>


                                <?php endforeach; ?>


                            </select>


                        </div>



                        <button
                            type="submit"
                            class="btn btn-warning"
                        >

                            Update Status

                        </button>


                        <a
                            href="index.php"
                            class="btn btn-secondary"
                        >

                            Cancel

                        </a>


                    </form>


                </div>


            </div>


        </div>


    </div>


</div>


</body>

</html>