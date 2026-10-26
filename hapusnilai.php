
<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Permintaan tidak valid.');
}

$nim = trim($_POST['NIM'] ?? '');

if ($nim === '') {
    exit('NIM mahasiswa tidak ditemukan.');
}

$sql = "DELETE FROM nilaimahasiswa WHERE NIM = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    exit('Gagal menyiapkan query: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "s", $nim);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) === 1) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header('Location: viewdata.php');
        exit;
    } else {
        echo "Data tidak ditemukan atau tidak ada data yang dihapus.";
    }
} else {
    echo "Gagal menghapus data.";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
