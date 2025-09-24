<?php	

//Including config.php file for connection with database 
	include_once('database/config.php');

//If the button Add Movie in movies.php is pressed, we will get datas that users added into the form, and insert them into database :
	if(isset($_POST['submit']))
	{

		$hotel_name = $_POST['hotel_name'];
		$hotel_desc = $_POST['hotel_desc'];
		$hotel_rating = $_POST['hotel_rating'];
		$hotel_image = $_POST['hotel_image'];
	

		$sql = "INSERT INTO hotels(hotel_name, hotel_desc, hotel_rating, hotel_image) VALUES (:hotel_name, :hotel_desc, :hotel_rating, :hotel_image)";

		$insertHotel = $conn->prepare($sql);
			

		$insertHotel->bindParam(':hotel_name', $hotel_name);
		$insertHotel->bindParam(':hotel_desc', $hotel_desc);
		$insertHotel->bindParam(':hotel_rating', $hotel_rating);
		$insertHotel->bindParam(':hotel_image', $hotel_image);

		$insertHotel->execute();

		// Set success message
		session_start();
		$_SESSION['success_message'] = "Hotel added successfully!";

		// Redirect to dashboard
		header("Location: dashboard.php");
		exit;
	}
?>