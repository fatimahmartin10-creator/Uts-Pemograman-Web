
<?php
require_once 'connect.php';

$sql = "DESCRIBE nilaimahasiswa";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

echo "<h2>Struktur Tabel nilaimahasiswa</h2>";
echo "<table border='1' cellpadding='8'>";
echo "<tr><th>Nama Kolom</th><th>Tipe Data</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
    echo "</tr>";
}

echo "</table>";
?>
