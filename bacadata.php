<?php
require_once __DIR__ . "/connect.php";

$sql = "SELECT * FROM myguests";
// Execute the SQL query
$result = mysqli_query($conn, $sql);

echo "<a href = 'formguest.php' > Tambah Data </a>";

// Process the result set
if (mysqli_num_rows($result) > 0) {
  // Output data of each row
  //echo "<h3>Data My Guest</h3>";
  echo "<table border=1 cellpadding=5>";
  echo "<caption> <strong>Data My Guest </strong></caption>";
  echo "<tr> 
             <th>id</th> 
             <th>Name</th> 
             <th>Email</th> 
             <th>Reg Date</th> 
        </tr>";
  while($row = mysqli_fetch_assoc($result)) {
    // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. " - Email: " . $row["email"] . " ". " - Reg Date: ". $row["reg_date"].  "<br>";
    echo "<tr> 
             <td> " . $row ["id"] . "</td> 
             <td> " . $row ["firstname"] . "" . $row["lastname"] . "</td> 
             <td> " . $row["email"] . "</td> 
             <td> " . $row["reg_date"]."</td> 
        </tr>";
    }
  echo "</table>";

} else {
  echo "0 results - Data Not Found!";
}

mysqli_close($conn);
?>