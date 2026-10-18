<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";
require_once "../../includes/validation.php";

$message = "";


// ========================================
// ADD CAB
// ========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cab_number = trim($_POST["cab_number"]);
    $cab_type = trim($_POST["cab_type"]);
    $driver_name = trim($_POST["driver_name"]);
    $driver_phone = normalize_phone_number($_POST["driver_phone"] ?? "");
    $fare_per_km = floatval($_POST["fare_per_km"]);
    $status = $_POST["status"] ?? "Available";

    if (
        empty($cab_type) ||
        empty($cab_number) ||
        empty($driver_name) ||
        empty($driver_phone) ||
        $fare_per_km <= 0
    ) {

        $message = "
            <div class='alert alert-danger'>
                Please fill all fields correctly.
            </div>
        ";

    } elseif (!is_valid_phone_number($driver_phone)) {

        $message = "
            <div class='alert alert-danger'>
                Driver phone number must be exactly 10 digits.
            </div>
        ";

    } else {
        $check = $conn->prepare(
            "SELECT id
             FROM cabs
             WHERE cab_number = ?"
        );

        $check->bind_param("s", $cab_number);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = "
                <div class='alert alert-danger'>
                    Cab number already exists.
                </div>
            ";
        } else {
            $stmt = $conn->prepare(
                "INSERT INTO cabs (
                    cab_number,
                    cab_type,
                    driver_name,
                    driver_phone,
                    fare_per_km,
                    status
                )
                    VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssssds",
                $cab_number,
                $cab_type,
                $driver_name,
                $driver_phone,
                $fare_per_km,
                $status
            );

            if ($stmt->execute()) {
                header("Location: index.php?message=added");
                exit();
            }

            $message = "
                <div class='alert alert-danger'>
                    Failed to add cab.
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

    <title>Add Cab - CabGo</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


</head>


<body class="bg-light">


<div class="container py-5">


    <div class="row justify-content-center">


        <div class="col-md-7">


            <div class="card shadow border-0">


                <div class="card-header bg-dark text-white">

                    <h4 class="mb-0">

                        Add New Cab

                    </h4>

                </div>


                <div class="card-body p-4">


                    <?php echo $message; ?>


                    <form method="POST">


                        <!-- CAB TYPE -->

                        <div class="mb-3">

                            <label class="form-label">

                                Cab Type

                            </label>


                            <select
                                name="cab_type"
                                class="form-select"
                                required
                            >

                                <option value="">

                                    Select Cab Type

                                </option>


                                <option value="Mini">

                                    Mini

                                </option>


                                <option value="Sedan">

                                    Sedan

                                </option>


                                <option value="SUV">

                                    SUV

                                </option>


                                <option value="Luxury">

                                    Luxury

                                </option>


                            </select>

                        </div>



                        <!-- CAB NUMBER -->

                        <div class="mb-3">

                            <label class="form-label">

                                Cab Number

                            </label>


                            <input
                                type="text"
                                name="cab_number"
                                class="form-control"
                                placeholder="Example: GJ05AB1234"
                                required
                            >

                        </div>



                        <!-- DRIVER NAME -->

                        <div class="mb-3">

                            <label class="form-label">

                                Driver Name

                            </label>


                            <input
                                type="text"
                                name="driver_name"
                                class="form-control"
                                placeholder="Enter driver name"
                                required
                            >

                        </div>



                        <!-- DRIVER PHONE -->

                        <div class="mb-3">

                            <label class="form-label">

                                Driver Phone

                            </label>


                            <input
                                type="tel"
                                name="driver_phone"
                                class="form-control"
                                placeholder="Enter 10-digit driver phone number"
                                inputmode="numeric"
                                pattern="[0-9]{10}"
                                maxlength="10"
                                required
                            >

                        </div>



                        <!-- FARE PER KM -->

                        <div class="mb-3">

                            <label class="form-label">

                                Fare Per KM (₹)

                            </label>


                            <input
                                type="number"
                                name="fare_per_km"
                                class="form-control"
                                step="0.01"
                                min="1"
                                required
                            >

                        </div>



                        <!-- STATUS -->

                        <div class="mb-4">

                            <label class="form-label">

                                Status

                            </label>


                            <select
                                name="status"
                                class="form-select"
                                required
                            >

                                <option value="Available">

                                    Available

                                </option>


                                <option value="Unavailable">

                                    Unavailable

                                </option>


                            </select>

                        </div>



                        <!-- BUTTON -->

                        <button
                            type="submit"
                            class="btn btn-warning"
                        >

                            Add Cab

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