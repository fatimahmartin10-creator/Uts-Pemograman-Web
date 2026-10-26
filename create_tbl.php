<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db5a";

// Create connection
$conn = mysqli_connect ($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// sql to create table
$sql = "CREATE TABLE galerry (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
namafile VARCHAR(30) NOT NULL,
upload_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
                    ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn,$sql)) {
  echo "Table created successfully";
} else {
  echo "Error creating table: " . mysqli_error;
}

mysqli_close($conn);
?>