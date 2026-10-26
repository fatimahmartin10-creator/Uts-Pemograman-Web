
<?php
require_once 'connect.php';

$sql = "ALTER TABLE nilaimahasiswa
    ADD COLUMN foto VARCHAR(255) DEFAULT NULL,
    ADD COLUMN created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN modified_date DATETIME DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP";

if (mysqli_query($conn, $sql)) {
    echo "Kolom berhasil ditambahkan!";
} else {
    echo "Terjadi kesalahan: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
