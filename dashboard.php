<?php 
/*Creating a session  based on a session identifier, passed via a GET or POST request.
  We will include config.php for connection with database.
  We will fetch all datas from users in database and show them.
  If a user is admin, he can update or delete a user data.
  */
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
    
    // Fetch movies
    $sql_hotels = "SELECT * FROM hotels";
    $selectHotels = $conn->prepare($sql_hotels);
    
    try {
        // Try to run the query
        $selectHotels->execute();
        $hotels_data = $selectHotels->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Set empty array if error
        $hotels_data = [];
    }


 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Dashboard</title>
 	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
 	 <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
  	<link rel="apple-touch-icon" href="/docs/5.1/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
	<link rel="icon" href="/docs/5.1/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
	<link rel="icon" href="/docs/5.1/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
	<link rel="manifest" href="/docs/5.1/assets/img/favicons/manifest.json">
	<link rel="mask-icon" href="/docs/5.1/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
	<link rel="icon" href="/docs/5.1/assets/img/favicons/favicon.ico">
	<meta name="theme-color" content="#7952b3">
 </head>
 <body>
 
 
 <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#"><?php echo "Welcome to dashboard ".$_SESSION['username']; ?></a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <input class="form-control form-control-dark w-50" type="text" placeholder="Search" aria-label="Search">
  <div class="navbar-nav">
    <div class="nav-item text-nowrap">
      <a class="nav-link px-3" href="logout.php">Sign out</a>
    </div>
  </div>
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
           <?php if ($_SESSION['is_admin'] == 'true') { ?>
            <li class="nav-item">
              <a class="nav-link" href="index.php">
                <span data-feather="file"></span>
                Home
              </a>
            </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="dashboard.php">
              <span data-feather="home"></span>
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="list_movies.php">
              <span data-feather="file"></span>
              Movies
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="bookings.php">
              <span ></span>
              Bookings
            </a>
          </li>
        </ul>
        <?php }else {?>
          <li class="nav-item">
              <a class="nav-link" href="index.php">
               
                Home
              </a>
            </li>
          <li class="nav-item">
          <a class="nav-link" href="bookings.php">
            <span ></span>
            Bookings
          </a>
        </li>
        </ul>
      <?php
      } ?>

        
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
      </div>
      
      <!-- Display success message if exists -->
      <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?php echo $_SESSION['success_message']; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
      <?php endif; ?>
      
      <!-- Display error message if exists -->
      <?php if(isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?php echo $_SESSION['error_message']; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>

    <?php if ($_SESSION['is_admin'] == 'true') { ?>

      
      
      
      
      <h2>Users</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">Id</th>
              <th scope="col">Emri</th>
              <th scope="col">Username</th>
              <th scope="col">Email</th>
              <th scope="col">Is Admin</th>
              <th scope="col">Update</th>
              <th scope="col">Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users_data as $user_data) { ?>

               <tr>
                <td><?php echo $user_data['id']; ?></td>
                <td><?php echo $user_data['emri']; ?></td>
                <td><?php echo $user_data['username']; ?></td>
                <td><?php echo $user_data['email']; ?></td>
                <td><?php echo $user_data['is_admin']; ?></td>
                <!-- If we want to update a user we need to link into editUsers.php -->
                <td><a href="updateUsers.php?id=<?= $user_data['id'];?>">Update</a></td>
                  <!-- If we want to delete a user we need to link into deleteUsers.php -->
                <td><a href="deleteUsers.php?id=<?= $user_data['id'];?>">Delete</a></td>
              </tr>

                
              
           <?php  } ?>
            
            
          </tbody>
        </table>
      </div>
     <?php  } else {
      
    } ?>
    
    
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">Id</th>
              <th scope="col">Hotel Name</th>
              <th scope="col">Description</th>
              <th scope="col">Rating</th>
              <th scope="col">Delete</th>
            </tr>
          </thead>

          <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
            <h2>Hotels</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMovieModal">
             Add New Hotel Booking
             </button>
          </div>
          <tbody>
            <?php if ($_SESSION['is_admin'] == 'true') { 
                  // Debug: Print the movies data as JSON for debugging
                  echo "<!-- Debug: " . json_encode($hotels_data) . " -->";

                  
                  
                  // Loop through each movie
                  if (count($hotels_data) > 0) {
                      foreach ($hotels_data as $hotel) { ?>
                         <tr>
                          <td><?php echo $hotel['id']; ?></td>
                          <td><?php echo $hotel['hotel_name']; ?></td>
                          <td><?php echo substr($hotel['hotel_desc'], 0, 50) . '...'; ?></td>
                          <td><?php echo $hotel['hotel_rating']; ?></td>
                          <td><a href="delete_movie.php?id=<?= $hotel['id'];?>" class="text-danger" onclick="return confirm('Are you sure you want to delete this hotel booking?');">Delete</a></td>
                        </tr>
                  <?php }
                  } else { ?>
                      <tr>
                          <td colspan="6" class="text-center">No hotels found</td>
                      </tr>
                  <?php }
            } else { ?>
                <tr>
                    <td colspan="6" class="text-center">You must be an admin to view hotels</td>
                </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

  <!-- Add Movie Modal -->
  <div class="modal fade" id="addMovieModal" tabindex="-1" aria-labelledby="addMovieModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addMovieModalLabel">Add New Hotel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="addhotel.php" method="post">
            <div class="mb-3">
              <label for="hotel_name" class="form-label">Hotel Name</label>
              <input type="text" class="form-control" id="hotel_name" name="hotel_name" required>
            </div>
            <div class="mb-3">
              <label for="hotel_desc" class="form-label">Hotel Description</label>
              <textarea class="form-control" id="hotel_desc" name="hotel_desc" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="hotel_rating" class="form-label">Rating (1-5 Stars)</label>
              <input type="number" class="form-control" id="hotel_rating" name="hotel_rating" min="1" max="5" required>
            </div>
            <div class="mb-3">
              <label for="hotel_image" class="form-label">Image Filename</label>
              <input type="text" class="form-control" id="hotel_image" name="hotel_image" placeholder="example.jpg" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="submit">Add Hotel Booking</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
  </body>
</html>


 </body>
 </html>