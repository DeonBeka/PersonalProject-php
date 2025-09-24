CREATE TABLE `users` (
  `id` int(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `emri` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `confirm_password` varchar(255) NOT NULL,
  `is_admin` varchar(255) NOT NULL
)

CREATE TABLE `hotels` (
  `id` int(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `hotel_name` varchar(255) NOT NULL,
  `hotel_desc` varchar(255) NOT NULL,
  `hotel_rating` varchar(255) NOT NULL,
)