<?php 
  session_start();
  include_once('database/config.php');

  if (empty($_SESSION['username'])) {
      header("Location: login.php");
  }

  // Fetch users
  $sql = "SELECT * FROM users";
  $selectUsers = $conn->prepare($sql);
  $selectUsers->execute();
  $users_data = $selectUsers->fetchAll();

  // Fetch hotels
  $sql_hotels = "SELECT * FROM hotels";
  $selectHotels = $conn->prepare($sql_hotels);
  try {
      $selectHotels->execute();
      $hotels_data = $selectHotels->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
      $hotels_data = [];
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f6fa;
    }
    .navbar {
      background: #2d3436 !important;
    }
    .navbar .navbar-brand {
      font-weight: bold;
      color: #fff;
    }
    .navbar .nav-link {
      color: #fff !important;
    }

    /* Sidebar */
    #sidebarMenu {
      background: #1e272e;
      min-height: 100vh;
    }
    #sidebarMenu .nav-link {
      color: #d2dae2;
      padding: 12px 20px;
      border-radius: 8px;
      margin: 4px 8px;
      transition: 0.2s;
    }
    #sidebarMenu .nav-link:hover,
    #sidebarMenu .nav-link.active {
      background: #3742fa;
      color: #fff;
    }

    /* Content */
    main {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      margin-top: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    h1.h2 {
      font-weight: bold;
      color: #2d3436;
    }

    /* Tables */
    .table {
      border-radius: 10px;
      overflow: hidden;
    }
    .table thead {
      background: #3742fa;
      color: #fff;
    }
    .table tbody tr:hover {
      background: #f1f2f6;
    }
    .table td, .table th {
      vertical-align: middle;
    }

    /* Buttons */
    .btn-primary {
      background: #3742fa;
      border: none;
      border-radius: 8px;
    }
    .btn-primary:hover {
      background: #2f34c0;
    }
    .btn-danger {
      border-radius: 8px;
    }

    /* Modal */
    .modal-content {
      border-radius: 12px;
    }
    .modal-header {
      background: #3742fa;
      color: #fff;
      border-radius: 12px 12px 0 0;
    }
  </style>
</head>
<body>

<header class="navbar navbar-dark sticky-top shadow">
  <a class="navbar-brand px-3" href="#">
    <?php echo "Welcome, ".$_SESSION['username']; ?>
  </a>
  <div class="navbar-nav ms-auto">
    <a class="nav-link px-3" href="logout.php">Sign out</a>
  </div>
</header>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <?php if ($_SESSION['is_admin'] == 'true') { ?>
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="bookings.php">Bookings</a></li>
          <?php } else { ?>
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="bookings.php">Bookings</a></li>
          <?php } ?>
        </ul>
      </div>
    </nav>

    <!-- Main -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <h1 class="h2 mb-4">Dashboard</h1>

      <!-- Alerts -->
      <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
      <?php endif; ?>
      <?php if(isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
      <?php endif; ?>

      <?php if ($_SESSION['is_admin'] == 'true') { ?>
        <!-- Users Table -->
        <h2>Users</h2>
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Id</th>
              <th>Emri</th>
              <th>Username</th>
              <th>Email</th>
              <th>Is Admin</th>
              <th>Update</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users_data as $user_data) { ?>
              <tr>
                <td><?= $user_data['id']; ?></td>
                <td><?= $user_data['emri']; ?></td>
                <td><?= $user_data['username']; ?></td>
                <td><?= $user_data['email']; ?></td>
                <td><?= $user_data['is_admin']; ?></td>
                <td><a href="updateUsers.php?id=<?= $user_data['id'];?>" class="btn btn-sm btn-warning">Update</a></td>
                <td><a href="deleteUsers.php?id=<?= $user_data['id'];?>" class="btn btn-sm btn-danger">Delete</a></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <?php } ?>

      <!-- Hotels Table -->
      <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
        <h2>Hotels</h2>
        <?php if ($_SESSION['is_admin'] == 'true') { ?>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMovieModal">+ Add New Hotel</button>
        <?php } ?>
      </div>

      <table class="table table-hover">
        <thead>
          <tr>
            <th>Id</th>
            <th>Hotel Name</th>
            <th>Description</th>
            <th>Rating</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($_SESSION['is_admin'] == 'true') {
            if (count($hotels_data) > 0) {
              foreach ($hotels_data as $hotel) { ?>
                <tr>
                  <td><?= $hotel['id']; ?></td>
                  <td><?= $hotel['hotel_name']; ?></td>
                  <td><?= substr($hotel['hotel_desc'], 0, 50) . '...'; ?></td>
                  <td><?= $hotel['hotel_rating']; ?></td>
                  <td><a href="delete_movie.php?id=<?= $hotel['id'];?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this hotel?')">Delete</a></td>
                </tr>
          <?php }
            } else { ?>
              <tr><td colspan="5" class="text-center">No hotels found</td></tr>
          <?php }
          } else { ?>
            <tr><td colspan="5" class="text-center">You must be an admin to view hotels</td></tr>
          <?php } ?>
        </tbody>
      </table>
    </main>
  </div>
</div>

<!-- Add Hotel Modal -->
<div class="modal fade" id="addMovieModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Hotel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="addhotel.php" method="post">
          <div class="mb-3">
            <label for="hotel_name" class="form-label">Hotel Name</label>
            <input type="text" class="form-control" id="hotel_name" name="hotel_name" required>
          </div>
          <div class="mb-3">
            <label for="hotel_desc" class="form-label">Description</label>
            <textarea class="form-control" id="hotel_desc" name="hotel_desc" required></textarea>
          </div>
          <div class="mb-3">
            <label for="hotel_rating" class="form-label">Rating</label>
            <input type="number" class="form-control" id="hotel_rating" name="hotel_rating" min="1" max="5" required>
          </div>
          <div class="mb-3">
            <label for="hotel_image" class="form-label">Image Filename</label>
            <input type="text" class="form-control" id="hotel_image" name="hotel_image" required>
          </div>
          <button type="submit" class="btn btn-primary">Add Hotel</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
