<?php 
	session_start();
	include_once('database/config.php');

	$id = $_GET['id'];
	$_SESSION['hotel_id'] = $id;

	$sql = "SELECT * FROM hotels WHERE id=:id";
	$selectHotels = $conn->prepare($sql);
	$selectHotels->bindParam(":id",$id);
	$selectHotels->execute();
	$hotel_data = $selectHotels->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo htmlspecialchars($hotel_data['hotel_name']); ?> - StayFinder</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Bootstrap 5.3 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Google Fonts -->
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
			background: rgba(255,255,255,0.9);
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

		/* Hotel Card */
		.hotel-detail-card {
			border: none;
			border-radius: 1rem;
			overflow: hidden;
			box-shadow: 0 8px 20px rgba(0,0,0,0.1);
			background: #fff;
		}
		.hotel-detail-card img {
			border-radius: 1rem;
			object-fit: cover;
			width: 100%;
			height: 100%;
		}

		/* Form Styling */
		.form-floating {
			margin: 15px 0;
		}
		.form-control {
			border-radius: 0.75rem;
			padding: 0.9rem;
			font-size: 1rem;
		}
		.btn-primary {
			background: linear-gradient(135deg, #5a2dc4, #2b92ff);
			border: none;
			border-radius: 0.75rem;
			padding: 0.9rem;
			font-weight: 600;
			transition: 0.3s;
		}
		.btn-primary:hover {
			background: linear-gradient(135deg, #2b92ff, #5a2dc4);
			transform: translateY(-2px);
		}

		/* Footer */
		.site-footer {
			background: linear-gradient(135deg, #5a2dc4, #2b92ff);
			color: #eee;
			padding: 2rem 0;
			margin-top: 3rem;
		}
		.site-footer a { color: #eee; }
		.site-footer a:hover { color: #fff; }
	</style>
</head>
<body>

<!-- Navbar -->
<header class="site-header fixed-top">
	<nav class="navbar navbar-expand-lg container">
		<a class="navbar-brand" href="index.php">StayFinder</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarMain">
			<ul class="navbar-nav ms-auto">
				<li class="nav-item"><a class="nav-link" href="index.php#hotels">Hotels</a></li>
				<li class="nav-item"><a class="nav-link" href="index.php#about">About</a></li>
				<?php if (isset($_SESSION['user_id'])): ?>
					<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
				<?php else: ?>
					<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
				<?php endif; ?>
			</ul>
		</div>
	</nav>
</header>

<!-- Main Content -->
<main class="container" style="padding-top:100px;">
	<section class="py-5">
		<div class="row justify-content-center">
			<div class="col-lg-10">
				<div class="card hotel-detail-card p-4">
					<div class="row g-4 align-items-center">
						<!-- Hotel Image -->
						<div class="col-md-5">
							<img src="hotel_images/<?php echo $hotel_data['hotel_image']; ?>" alt="Hotel Image">
						</div>

						<!-- Hotel Info + Booking -->
						<div class="col-md-7">
							<h2 class="fw-bold mb-3"><?php echo htmlspecialchars($hotel_data['hotel_name']); ?></h2>
							<p class="text-muted"><?php echo htmlspecialchars($hotel_data['hotel_desc']); ?></p>

							<form action="book.php" method="post">
								<div class="form-floating">
									<input type="number" class="form-control" id="nights" placeholder="Number of Nights" name="nr_tickets" required>
									<label for="nights">Number of Nights</label>
								</div>
								<div class="form-floating">
									<input type="date" class="form-control" id="start_date" name="start_date" required>
									<label for="start_date">From</label>
								</div>
								<div class="form-floating">
									<input type="date" class="form-control" id="end_date" name="end_date" required>
									<label for="end_date">To</label>
								</div>
								<button class="w-100 btn btn-lg btn-primary mt-3" type="submit" name="submit">Book Now</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<!-- Footer -->
<footer class="site-footer">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h5>StayFinder</h5>
				<p>Connecting you with great places to stay worldwide.</p>
			</div>
			<div class="col-md-6 text-md-end">
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
