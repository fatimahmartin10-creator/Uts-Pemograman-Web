<?php
//definisi nilai parameter server
$servername = "localhost";
$username = "root";
$password = "";
//$dbname = "mydb";

// Membuat koneksi ke db
//$conn = mysqli_connect("localhost", "root", "");
$conn = mysqli_connect($servername, $username, $password);

// Memeriksa koneksi berhasil atau tidak
if ($conn->connect_error) {
    //jika koneksi gagal
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

// Membuat sebuah database dengan nama: db5a
$sql = "CREATE DATABASE db5a";
if ($conn->query($sql) === TRUE) {
  echo "Database db5a created successfully";
} else {
  echo "Error creating database: " . $conn->error;
}

// Close connection
$conn->close();
?>