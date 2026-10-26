
<?php
require_once 'connect.php';

$nim = trim($_GET['NIM'] ?? '');

if ($nim === '') {
    exit('NIM tidak ditemukan.');
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM nilaimahasiswa WHERE NIM = ?"
);
mysqli_stmt_bind_param($stmt, "s", $nim);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    exit('Data mahasiswa tidak ditemukan.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            padding: 25px;
            color: #1f2937;
        }
        .card {
            max-width: 500px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px #0001;
        }
        h2 { margin-top: 0; }
        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 7px;
        }
        input {
            width: 100%;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            box-sizing: border-box;
        }
        button, .back {
            display: inline-block;
            padding: 11px 16px;
            margin-top: 20px;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }
        button { background: #1670f8; color: white; }
        .back { background: #e5e7eb; color: #1f2937; }
        img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            margin-top: 8px;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>✏️ Edit Data Mahasiswa</h2>

    <form action="editnilai.php" method="POST"
          enctype="multipart/form-data">

        <label>NIM</label>
        <input type="text" name="NIM"
               value="<?= htmlspecialchars($data['NIM']) ?>" readonly>

        <label>Nama Mahasiswa</label>
        <input type="text" name="NamaMahasiswa"
               value="<?= htmlspecialchars($data['NamaMahasiswa']) ?>"
               required maxlength="30">

        <label>Nilai Akhir</label>
        <input type="number" name="NilaiAkhir"
               value="<?= htmlspecialchars($data['NilaiAkhir']) ?>"
               min="0" max="100" step="0.01" required>

        <label>Foto Saat Ini</label>
        <?php if (!empty($data['foto'])) : ?>
            <img src="uploads/<?= rawurlencode(basename($data['foto'])) ?>"
                 alt="Foto mahasiswa">
        <?php else : ?>
            <p>Belum ada foto.</p>
        <?php endif; ?>

        <label>Ganti Foto (opsional)</label>
        <input type="file" name="foto"
               accept=".jpg,.jpeg,.png,.gif,.webp">

        <p style="font-size:12px;color:#64748b">
            Kosongkan foto jika tidak ingin menggantinya.
        </p>

        <button type="submit">Simpan Perubahan</button>
        <a href="viewdata.php" class="back">Kembali</a>
    </form>
</div>

</body>
</html>
<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
