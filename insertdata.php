<?php
// koneksi ke db
require_once __DIR__ . "/connect.php";

// data yang akan diisi ke tabel
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];

// kueri insert data ke dalam tabel
$sql = "INSERT INTO MyGuests (firstname, lastname, email)
VALUES ('$fname', '$lname', '$email')";

if (mysqli_query($conn, $sql)) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

header('Location: bacadata.php');
//tutup koneksi ke db
//mysqli_close($conn);

// koneksi ke db
//require_once __DIR__ . "/connect.php";

// kueri insert data ke dalam tabel
//$sql = "INSERT INTO MyGuests (firstname, lastname, email)
//VALUES ('John', 'Doe', 'john@example.com')";

//if (mysqli_query($conn, $sql)) {
//  echo "New record created successfully";
//} else {
//  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
//}

//mysqli_close($conn);

// koneksi ke db
//require_once __DIR__ . "/connect.php";

// data yang akan diisi ke tabel
//$fname = "Muhammad";
//$lname = "Nabil";
//$email = "nabil@gmail.com";

// kueri insert data ke dalam tabel
//$sql = "INSERT INTO MyGuests (firstname, lastname, email)
//VALUES ('$fname', '$lname', '$email')";

//if (mysqli_query($conn, $sql)) {
//  echo "New record created successfully";
//} else {
//  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
//}

//mysqli_close($conn);

?>