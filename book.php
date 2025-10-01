<?php 
 /*Creating a session  based on a session identifier, passed via a GET or POST request.
  We will include config.php for connection with database.
  */

	session_start();

	include_once('database/config.php');

	//Getting values 'id' and 'movie_id' using $_SESSION
	$user_id = $_SESSION['id'];
    $hotel_id = $_SESSION['hotel_id'];

	//Getting some of data from details.php form
	$nr_nights = $_POST['nr_nights'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	//Inserting the new data into database
	$sql = "INSERT INTO bookings(user_id, hotel_id, nr_nights, start_date, end_date) VALUES (:user_id, :hotel_id, :nr_nights, :start_date, :end_date)";

	$insertBooking = $conn->prepare($sql);

	$insertBooking->bindParam(":user_id", $user_id);
	$insertBooking->bindParam(":hotel_id", $hotel_id);
	$insertBooking->bindParam(":nr_nights", $nr_nights);
	$insertBooking->bindParam(":start_date", $start_date);
	$insertBooking->bindParam(":end_date", $end_date);

	$insertBooking->execute();

	header("Location: index.php");

 ?>