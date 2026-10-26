
<?php
require_once __DIR__ . '/connect.php';

// Ambil data dari formulir
$nim = trim($_POST['NIM'] ?? '');
$nama = trim($_POST['NamaMahasiswa'] ?? '');
$nilai = isset($_POST['NilaiAkhir'])
    ? (float) $_POST['NilaiAkhir']
    : -1;

// Validasi data
if (
    $_SERVER['REQUEST_METHOD'] !== 'POST' ||
    $nim === '' ||
    $nama === '' ||
    !isset($_POST['NilaiAkhir']) ||
    $nilai < 0 ||
    $nilai > 100
) {
    exit('Data tidak lengkap atau nilai tidak valid.');
}

// Konversi nilai
if ($nilai <= 34.50) {
    $huruf = 'E';
    $predikat = 'Tidak Lulus';
} elseif ($nilai <= 50.50) {
    $huruf = 'D';
    $predikat = 'Kurang Memuaskan';
} elseif ($nilai <= 65.50) {
    $huruf = 'C';
    $predikat = 'Cukup Memuaskan';
} elseif ($nilai <= 80.50) {
    $huruf = 'B';
    $predikat = 'Memuaskan';
} else {
    $huruf = 'A';
    $predikat = 'Sangat Memuaskan';
}

// Upload foto bersifat opsional
$foto = null;

if (
    isset($_FILES['foto']) &&
    $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE
) {
    if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        exit('Foto gagal diunggah.');
    }

    $tmp = $_FILES['foto']['tmp_name'];
    $ukuran = $_FILES['foto']['size'];
    $tipe = mime_content_type($tmp);

    $tipeDiizinkan = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp'
    ];

    if (
        $ukuran > 5000000 ||
        !isset($tipeDiizinkan[$tipe]) ||
        getimagesize($tmp) === false
    ) {
        exit('Foto harus berupa JPG, PNG, GIF, atau WEBP dengan ukuran maksimal 5 MB.');
    }

    $ekstensi = $tipeDiizinkan[$tipe];
    $foto = uniqid('foto_', true) . '.' . $ekstensi;
    $folder = __DIR__ . '/uploads/';

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    if (!move_uploaded_file($tmp, $folder . $foto)) {
        exit('Foto gagal disimpan.');
    }
}

// Simpan data menggunakan prepared statement
$sql = "INSERT INTO nilaimahasiswa
    (NIM, NamaMahasiswa, NilaiAkhir, NilaiHuruf,
     Predikat, foto, created_date, modified_date)
    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    exit('Query gagal disiapkan: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    'ssdsss',
    $nim,
    $nama,
    $nilai,
    $huruf,
    $predikat,
    $foto
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header('Location: bacadata.php');
    exit;
}

if ($foto !== null && file_exists(__DIR__ . '/uploads/' . $foto)) {
    unlink(__DIR__ . '/uploads/' . $foto);
}

$error = mysqli_stmt_error($stmt);
mysqli_stmt_close($stmt);
mysqli_close($conn);

exit('Data gagal disimpan: ' . htmlspecialchars($error));
?>
