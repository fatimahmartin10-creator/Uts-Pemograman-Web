
<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: viewdata.php');
    exit;
}

$nim = trim($_POST['NIM'] ?? '');
$nama = trim($_POST['NamaMahasiswa'] ?? '');
$nilaiInput = $_POST['NilaiAkhir'] ?? '';

if ($nim === '' || $nama === '' || $nilaiInput === '' ||
    !is_numeric($nilaiInput)) {
    exit('Data tidak lengkap atau nilai tidak valid.');
}

$nilai = (float)$nilaiInput;

if ($nilai < 0 || $nilai > 100 || strlen($nama) > 30) {
    exit('Nilai harus 0–100 dan nama maksimal 30 karakter.');
}

// Hitung nilai huruf dan predikat
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

// Ambil foto lama
$stmt = mysqli_prepare(
    $conn,
    "SELECT foto FROM nilaimahasiswa WHERE NIM = ?"
);
mysqli_stmt_bind_param($stmt, "s", $nim);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$lama = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$lama) {
    exit('Data mahasiswa tidak ditemukan.');
}

$foto = $lama['foto'];
$fotoBaru = null;

// Proses foto jika ada file baru
if (isset($_FILES['foto']) &&
    $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        exit('Upload foto gagal.');
    }

    if ($_FILES['foto']['size'] > 500000) {
        exit('Ukuran foto maksimal 500 KB.');
    }

    $info = getimagesize($_FILES['foto']['tmp_name']);
    $tipe = $info['mime'] ?? '';

    $ekstensiDiizinkan = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp'
    ];

    if (!$info || !isset($ekstensiDiizinkan[$tipe])) {
        exit('Format foto harus JPG, PNG, GIF, atau WEBP.');
    }

    $folder = __DIR__ . '/uploads/';

    if (!is_dir($folder) &&
        !mkdir($folder, 0755, true)) {
        exit('Folder upload tidak dapat dibuat.');
    }

    $namaFile = 'foto_' . bin2hex(random_bytes(8)) . '.' .
                $ekstensiDiizinkan[$tipe];

    if (!move_uploaded_file(
        $_FILES['foto']['tmp_name'],
        $folder . $namaFile
    )) {
        exit('Foto gagal disimpan.');
    }

    $fotoBaru = $namaFile;
    $foto = $namaFile;
}

// Simpan perubahan; modified_date diperbarui otomatis
$sql = "UPDATE nilaimahasiswa
        SET NamaMahasiswa = ?,
            NilaiAkhir = ?,
            NilaiHuruf = ?,
            Predikat = ?,
            foto = ?
        WHERE NIM = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sdssss",
    $nama,
    $nilai,
    $huruf,
    $predikat,
    $foto,
    $nim
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header('Location: viewdata.php');
    exit;
}

// Jika penyimpanan gagal, hapus foto baru yang terlanjur diunggah
if ($fotoBaru !== null) {
    @unlink(__DIR__ . '/uploads/' . $fotoBaru);
}

echo 'Gagal menyimpan perubahan data.';
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
