<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CabGo - Book Your Ride</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
        }

        /* Navbar */
        .navbar {
            background: #0b1c2c;
            padding: 15px 0;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: #ffc107 !important;
        }

        .navbar-brand i {
            margin-right: 5px;
        }

        .nav-link {
            color: white !important;
            margin: 0 10px;
        }

        .btn-register {
            background: #ffc107;
            color: #000;
            border: none;
            font-weight: 600;
        }

        .btn-register:hover {
            background: #e0a800;
        }

        /* Hero Section */
        .hero {
            min-height: 650px;
            background:
                linear-gradient(rgba(5, 20, 35, 0.85),
                    rgba(5, 20, 35, 0.85)),
                url("https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=1600&q=80");

            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            color: white;
        }

        .hero h1 {
            font-size: 52px;
            font-weight: bold;
        }

        .hero p {
            color: #d6d6d6;
            font-size: 18px;
        }

        /* Search Box */
        .search-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-top: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .search-box label {
            color: #333;
            font-weight: 600;
        }

        .btn-search {
            background: #ffc107 !important;
            color: #000 !important;
            border: none !important;
            width: 100%;
            height: 45px;
            font-weight: bold;
        }

        .btn-search:hover {
            background: #e0a800 !important;
            color: #000 !important;
            border: none !important;
        }

        /* Features */
        .features {
            padding: 70px 0;
        }

        .feature-card {
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
            height: 100%;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
            transition: 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-8px);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: #fff3cd;
            color: #ffc107;
            border-radius: 50%;
            margin: auto auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        /* Footer */
        footer {
            background: #0b1c2c;
            color: white;
            padding: 25px;
            text-align: center;
        }

        @media(max-width: 768px) {

            .hero {
                text-align: center;
                min-height: 600px;
            }

            .hero h1 {
                font-size: 38px;
            }
        }
    </style>
</head>
<script src="assets/js/script.js"></script>
<body>

    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg navbar-dark">

        <div class="container">

            <a class="navbar-brand" href="index.php">
                <i class="bi bi-taxi-front-fill"></i>CabGo
            </a>

            <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav mx-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>

                </ul>

                <div>

                    <?php if (isset($_SESSION['user_id'])): ?>

                        <a href="user/dashboard.php"
                            class="btn btn-warning">

                            Dashboard

                        </a>

                    <?php else: ?>

                        <a href="login.php"
                            class="btn btn-outline-light me-2">

                            Login

                        </a>

                        <a href="register.php"
                            class="btn btn-register">

                            Register

                        </a>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </nav>


    <!-- Hero Section -->

    <section class="hero">

        <div class="container">

            <div class="row">

                <div class="col-lg-7">

                    <h1>
                        Book Your Ride<br>
                        Anytime, Anywhere
                    </h1>

                    <p class="mt-3">
                        Safe, reliable and affordable cab rides at your fingertips.
                    </p>


                    <!-- Search Form -->

                    <div class="search-box">

                        <form action="login.php">

                            <div class="row g-3">

                                <div class="col-md-4">

                                    <label>Pickup Location</label>

                                    <input type="text"
                                        class="form-control"
                                        placeholder="Enter pickup location">

                                </div>


                                <div class="col-md-4">

                                    <label>Drop Location</label>

                                    <input type="text"
                                        class="form-control"
                                        placeholder="Enter drop location">

                                </div>


                                <div class="col-md-4 d-flex align-items-end">

                                    <button class="btn btn-search">

                                        Search Cabs

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- Features -->

    <section class="features" id="services">

        <div class="container">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="feature-card">

                        <div class="feature-icon">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>

                        <h4>Fast & Easy Booking</h4>

                        <p>
                            Book your ride quickly with a simple and easy booking process.
                        </p>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="feature-card">

                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>

                        <h4>Safe Rides</h4>

                        <p>
                            Your safety is our priority with trusted drivers and reliable cabs.
                        </p>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="feature-card">

                        <div class="feature-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>

                        <h4>Affordable Fares</h4>

                        <p>
                            Enjoy comfortable rides at affordable and transparent prices.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- About -->

    <section class="py-5 bg-light" id="about">

        <div class="container text-center">

            <h2 class="fw-bold">About CabGo</h2>

            <p class="mt-3">

                CabGo is a web-based cab booking management system that allows
                users to book cabs easily and enables administrators to manage
                users, cabs and bookings efficiently.

            </p>

        </div>

    </section>


    <!-- Footer -->

    <footer id="contact">

        <p class="mb-0">

            © <?php echo date("Y"); ?> CabGo.
            All Rights Reserved.

        </p>

    </footer>


    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>