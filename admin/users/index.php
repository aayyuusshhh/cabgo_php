<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";


// ========================================
// GET ALL USERS
// ========================================

$users = $conn->query(

    "SELECT 
        users.id,
        users.name,
        users.email,
        users.phone,
        users.role,
        users.created_at,
        COUNT(bookings.id) AS total_bookings

    FROM users

    LEFT JOIN bookings
        ON users.id = bookings.user_id

    GROUP BY users.id

    ORDER BY users.id DESC"

);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Users - CabGo</title>


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
            font-family: Arial, sans-serif;
        }


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

            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }


        .table-box {

            background: white;

            padding: 25px;

            border-radius: 12px;

            box-shadow: 0 3px 15px rgba(0,0,0,0.06);
        }


        .avatar {

            width: 40px;
            height: 40px;

            border-radius: 50%;

            background: #fff3cd;

            color: #856404;

            display: flex;

            justify-content: center;
            align-items: center;

            font-weight: bold;
        }


        .role-user {

            background: #cfe2ff;
            color: #084298;

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 12px;
        }


        .role-admin {

            background: #fff3cd;
            color: #856404;

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 12px;
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


    <a href="index.php" class="active">

        <i class="bi bi-people"></i>

        <span>Users</span>

    </a>


    <a href="../bookings/index.php">

        <i class="bi bi-calendar-check"></i>

        <span>Bookings</span>

    </a>


    <a href="../cabs/index.php">

        <i class="bi bi-taxi-front"></i>

        <span>Manage Cabs</span>

    </a>


    <hr>


    <a href="../../logout.php" class="text-danger">

        <i class="bi bi-box-arrow-right"></i>

        <span>Logout</span>

    </a>


</div>



<!-- MAIN CONTENT -->

<div class="main-content">


    <div class="topbar">

        <div>

            <h4 class="mb-0">Manage Users</h4>

            <small class="text-muted">
                View and manage all registered users
            </small>

        </div>


        <a href="add.php" class="btn btn-warning">

            <i class="bi bi-person-plus"></i>

            Add User

        </a>


    </div>



    <div class="table-box">


        <?php if (isset($_GET["message"])): ?>


            <?php if ($_GET["message"] == "added"): ?>

                <div class="alert alert-success">
                    User added successfully.
                </div>

            <?php elseif ($_GET["message"] == "updated"): ?>

                <div class="alert alert-success">
                    User updated successfully.
                </div>

            <?php elseif ($_GET["message"] == "deleted"): ?>

                <div class="alert alert-success">
                    User deleted successfully.
                </div>

            <?php elseif ($_GET["message"] == "error"): ?>

                <div class="alert alert-danger">
                    Something went wrong.
                </div>

            <?php endif; ?>


        <?php endif; ?>



        <div class="table-responsive">


            <table class="table table-hover align-middle">


                <thead class="table-light">

                    <tr>

                        <th>ID</th>

                        <th>User</th>

                        <th>Phone</th>

                        <th>Role</th>

                        <th>Bookings</th>

                        <th>Joined</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>


                    <?php if ($users->num_rows > 0): ?>


                        <?php while ($user = $users->fetch_assoc()): ?>


                            <tr>


                                <td>

                                    #<?php echo $user["id"]; ?>

                                </td>



                                <td>


                                    <div class="d-flex align-items-center gap-2">


                                        <div class="avatar">

                                            <?php

                                            echo strtoupper(
                                                substr(
                                                    $user["name"],
                                                    0,
                                                    1
                                                )
                                            );

                                            ?>

                                        </div>


                                        <div>

                                            <strong>

                                                <?php
                                                echo htmlspecialchars(
                                                    $user["name"]
                                                );
                                                ?>

                                            </strong>


                                            <br>


                                            <small class="text-muted">

                                                <?php
                                                echo htmlspecialchars(
                                                    $user["email"]
                                                );
                                                ?>

                                            </small>

                                        </div>


                                    </div>


                                </td>



                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $user["phone"]
                                    );
                                    ?>

                                </td>



                                <td>

                                    <span class="role-<?php
                                        echo strtolower($user["role"]);
                                    ?>">

                                        <?php
                                        echo ucfirst(
                                            htmlspecialchars(
                                                $user["role"]
                                            )
                                        );
                                        ?>

                                    </span>

                                </td>



                                <td>

                                    <span class="badge text-bg-dark">

                                        <?php
                                        echo $user["total_bookings"];
                                        ?>

                                    </span>

                                </td>



                                <td>

                                    <?php

                                    echo date(
                                        "d M Y",
                                        strtotime(
                                            $user["created_at"]
                                        )
                                    );

                                    ?>

                                </td>



                                <td>


                                    <a
                                        href="edit.php?id=<?php echo $user["id"]; ?>"
                                        class="btn btn-sm btn-primary"
                                    >

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    <?php if ($user["id"] != $_SESSION["user_id"]): ?>


                                        <a
                                            href="delete.php?id=<?php echo $user["id"]; ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this user?')"
                                        >

                                            <i class="bi bi-trash"></i>

                                        </a>


                                    <?php endif; ?>


                                </td>


                            </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="7"
                                class="text-center text-muted py-4"
                            >

                                No users found.

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