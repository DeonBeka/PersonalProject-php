<?php
session_start();
include_once('database/config.php');

$sql = "SELECT * FROM hotels";
$selectHotels = $conn->prepare($sql);
$selectHotels->execute();
$hotels_data = $selectHotels->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discover Hotels - StayFinder</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f9fafc;
            color: #333;
        }
        a { text-decoration: none; }

        /* Navbar */
        .site-header {
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.6rem;
            color: #5a2dc4 !important;
        }
        .nav-link {
            font-weight: 500;
            color: #444 !important;
        }
        .nav-link:hover {
            color: #5a2dc4 !important;
        }

        /* Hero Carousel */
        .hero-carousel .carousel-item {
            height: 70vh;
            min-height: 400px;
        }
        .hero-carousel img {
            object-fit: cover;
            height: 100%;
        }
        .hero-caption {
            position: absolute;
            bottom: 20%;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: #fff;
        }
        .hero-caption h1 {
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 0 4px 10px rgba(0,0,0,0.5);
        }
        .hero-caption p {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
        }

        /* Hotel Cards */
        .hotel-card {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 8px 18px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hotel-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 26px rgba(0,0,0,0.12);
        }
        .hotel-card img {
            height: 220px;
            object-fit: cover;
        }
        .card-body .card-title {
            font-size: 1.2rem;
            font-weight: 600;
        }
        .rating-stars {
            color: #f1c40f;
            font-size: 1.1rem;
        }

        /* Footer */
        .site-footer {
            background: linear-gradient(135deg, #5a2dc4, #2b92ff);
            color: #eee;
            padding: 2.5rem 0;
            margin-top: 3rem;
        }
        .site-footer a {
            color: #eee;
        }
        .site-footer a:hover {
            color: #fff;
        }
    </style>
</head>
<body>

<header class="site-header fixed-top">
    <nav class="navbar navbar-expand-lg container">
        <a class="navbar-brand" href="#">StayFinder</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#hotels">Hotels</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 'true'): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="hotel_images/hotel.png" class="d-block w-100" alt="Hotel 1">
            <div class="hero-caption">
                <h1>Find Your Perfect Stay</h1>
                <p>Book hotels with comfort, style, and ease.</p>
                <a href="#hotels" class="btn btn-lg btn-light">Browse Hotels</a>
            </div>
        </div>
        <div class="carousel-item">
            <img src="hotel_images/hoteli.png" class="d-block w-100" alt="Hotel 2">
            <div class="hero-caption">
                <h1>Luxury & Affordability</h1>
                <p>Discover stays that match your budget.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="hotel_images/hotelu.png" class="d-block w-100" alt="Hotel 3">
            <div class="hero-caption">
                <h1>Explore the World</h1>
                <p>With the best hotels at your fingertips.</p>
            </div>
        </div>
    </div>
</div>

<!-- Hotel Listings -->
<main class="py-5" id="hotels">
    <div class="container">
        <h2 class="mb-4 text-center fw-bold">Available Hotels</h2>
        <div class="row g-4">
            <?php foreach ($hotels_data as $hotel_data): ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card hotel-card h-100">
                        <img src="hotel_images/<?php echo $hotel_data['hotel_image']; ?>" alt="Hotel Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($hotel_data['hotel_name']); ?></h5>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($hotel_data['hotel_desc']); ?></p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center bg-white">
                            <div>
                                <?php
                                $stars = round($hotel_data['hotel_rating']);
                                for ($i = 0; $i < 5; $i++) {
                                    echo $i < $stars ? '★' : '☆';
                                }
                                ?>
                                <span class="badge bg-warning text-dark ms-1">
                                    <?php echo number_format($hotel_data['hotel_rating'], 1); ?>
                                </span>
                            </div>
                            <div>
                                <a href="view.php?id=<?php echo $hotel_data['id']; ?>" class="btn btn-sm btn-primary">View</a>
                                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 'true'): ?>
                                    <a href="edit.php?id=<?php echo $hotel_data['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<!-- About Section -->
<section id="about" class="py-5 bg-light text-center">
    <div class="container">
        <h2 class="fw-bold">About StayFinder</h2>
        <p class="lead text-muted">At StayFinder, we believe your travel accommodation should be seamless, beautiful, and reliable. Browse, book, and relax.</p>
    </div>
</section>

<!-- Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>StayFinder</h5>
                <p>Connecting you with great places to stay worldwide.</p>
            </div>
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="#hotels">Hotels</a></li>
                    <li><a href="#about">About</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-md-4 text-md-end">
                <h5>Contact</h5>
                <p>Email: support@stayfinder.com</p>
                <p>Phone: +1-800-123-4567</p>
            </div>
        </div>
        <div class="text-center mt-4">
            <small>&copy; <?php echo date("Y"); ?> StayFinder. All rights reserved.</small>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
