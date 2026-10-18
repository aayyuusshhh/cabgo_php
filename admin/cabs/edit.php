<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";
require_once "../../includes/validation.php";

$message = "";


// ========================================
// CHECK CAB ID
// ========================================

if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"])
) {

    header("Location: index.php");
    exit();

}


$cab_id = intval($_GET["id"]);


// ========================================
// GET CAB DETAILS
// ========================================

$stmt = $conn->prepare(

    "SELECT *
     FROM cabs
     WHERE id = ?"

);


$stmt->bind_param(
    "i",
    $cab_id
);


$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    header("Location: index.php");
    exit();

}


$cab = $result->fetch_assoc();


// ========================================
// UPDATE CAB
// ========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cab_type = trim($_POST["cab_type"]);
    $cab_number = strtoupper(trim($_POST["cab_number"]));
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
             WHERE cab_number = ?
             AND id != ?"
        );

        $check->bind_param("si", $cab_number, $cab_id);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {
            $message = "
                <div class='alert alert-danger'>
                    This cab number already belongs to another cab.
                </div>
            ";
        } else {
            $update = $conn->prepare(
                "UPDATE cabs SET
                    cab_number = ?,
                    cab_type = ?,
                    driver_name = ?,
                    driver_phone = ?,
                    fare_per_km = ?,
                    status = ?
                WHERE id = ?"
            );

            $update->bind_param(
                "ssssdsi",
                $cab_number,
                $cab_type,
                $driver_name,
                $driver_phone,
                $fare_per_km,
                $status,
                $cab_id
            );

            if ($update->execute()) {
                header("Location: index.php?message=updated");
                exit();
            }

            $message = "
                <div class='alert alert-danger'>
                    Failed to update cab.
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

    <title>Edit Cab - CabGo</title>


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

                        Edit Cab

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


                                <?php

                                $cab_types = [

                                    "Mini",
                                    "Sedan",
                                    "SUV",
                                    "Luxury"

                                ];


                                foreach ($cab_types as $type):

                                ?>


                                    <option
                                        value="<?php echo $type; ?>"

                                        <?php

                                        if (
                                            $cab["cab_type"] == $type
                                        ) {

                                            echo "selected";

                                        }

                                        ?>

                                    >

                                        <?php echo $type; ?>

                                    </option>


                                <?php endforeach; ?>


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
                                value="<?php
                                echo htmlspecialchars(
                                    $cab["cab_number"]
                                );
                                ?>"
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
                                value="<?php echo htmlspecialchars($cab["driver_name"] ?? ""); ?>"
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
                                value="<?php echo htmlspecialchars($cab["driver_phone"] ?? ""); ?>"
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
                                value="<?php
                                echo $cab["fare_per_km"];
                                ?>"
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


                                <option
                                    value="Available"

                                    <?php

                                    if (
                                        $cab["status"] == "Available"
                                    ) {

                                        echo "selected";

                                    }

                                    ?>

                                >

                                    Available

                                </option>


                                <option
                                    value="Unavailable"

                                    <?php

                                    if (
                                        $cab["status"] == "Unavailable"
                                    ) {

                                        echo "selected";

                                    }

                                    ?>

                                >

                                    Unavailable

                                </option>


                            </select>

                        </div>



                        <!-- BUTTONS -->

                        <button
                            type="submit"
                            class="btn btn-warning"
                        >

                            Update Cab

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