<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";
require_once "../../includes/validation.php";


// ========================================
// CHECK USER ID
// ========================================

if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"])
) {

    header("Location: index.php");
    exit();

}


$user_id = intval($_GET["id"]);


// ========================================
// GET USER
// ========================================

$stmt = $conn->prepare(

    "SELECT id, name, email, phone, role
     FROM users
     WHERE id = ?"

);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    header("Location: index.php");
    exit();

}


$user = $result->fetch_assoc();

$message = "";


// ========================================
// UPDATE USER
// ========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = normalize_phone_number($_POST["phone"] ?? "");
    $role = $_POST["role"] ?? "";

    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($role)
    ) {

        $message = "
            <div class='alert alert-danger'>
                All fields are required.
            </div>
        ";

    } elseif (!is_valid_email_address($email)) {

        $message = "
            <div class='alert alert-danger'>
                Invalid email address.
            </div>
        ";

    } elseif (!is_valid_phone_number($phone)) {

        $message = "
            <div class='alert alert-danger'>
                Phone number must be exactly 10 digits.
            </div>
        ";

    } elseif (email_exists($conn, $email, $user_id)) {

        $message = "
            <div class='alert alert-danger'>
                Email already belongs to another user.
            </div>
        ";

    } else {
        $update = $conn->prepare(
            "UPDATE users
            SET
                name = ?,
                email = ?,
                phone = ?,
                role = ?
            WHERE id = ?"
        );

        $update->bind_param(
            "ssssi",
            $name,
            $email,
            $phone,
            $role,
            $user_id
        );

        if ($update->execute()) {
            header("Location: index.php?message=updated");
            exit();
        }

        $message = "
            <div class='alert alert-danger'>
                Update failed.
            </div>
        ";
    }
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit User - CabGo</title>


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

                        Edit User

                    </h4>

                </div>


                <div class="card-body p-4">


                    <?php echo $message; ?>


                    <form method="POST">


                        <!-- NAME -->

                        <div class="mb-3">

                            <label class="form-label">

                                Full Name

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars($user["name"]);
                                ?>"
                                required
                            >

                        </div>



                        <!-- EMAIL -->

                        <div class="mb-3">

                            <label class="form-label">

                                Email

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars($user["email"]);
                                ?>"
                                required
                            >

                        </div>



                        <!-- PHONE -->

                        <div class="mb-3">

                            <label class="form-label">

                                Phone

                            </label>

                            <input
                                type="tel"
                                name="phone"
                                class="form-control"
                                value="<?php
                                    echo htmlspecialchars($user["phone"]);
                                ?>"
                                inputmode="numeric"
                                pattern="[0-9]{10}"
                                maxlength="10"
                                required
                            >

                        </div>



                        <!-- ROLE -->

                        <div class="mb-4">

                            <label class="form-label">

                                Role

                            </label>

                            <select
                                name="role"
                                class="form-select"
                            >

                                <option
                                    value="user"

                                    <?php

                                    if (
                                        $user["role"] == "user"
                                    ) {

                                        echo "selected";

                                    }

                                    ?>

                                >

                                    User

                                </option>


                                <option
                                    value="admin"

                                    <?php

                                    if (
                                        $user["role"] == "admin"
                                    ) {

                                        echo "selected";

                                    }

                                    ?>

                                >

                                    Admin

                                </option>


                            </select>

                        </div>



                        <button
                            type="submit"
                            class="btn btn-warning"
                        >

                            Update User

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