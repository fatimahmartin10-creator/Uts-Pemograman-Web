
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Nilai Mahasiswa</title>
</head>
<body>
    <h2>Form Nilai Mahasiswa</h2>

    <form action="viewdata_nilai.php" method="POST"
          enctype="multipart/form-data">

        <p>
            <label>NIM:</label><br>
            <input type="text" name="NIM" maxlength="10" required>
        </p>

        <p>
            <label>Nama Mahasiswa:</label><br>
            <input type="text" name="NamaMahasiswa"
                   maxlength="30" required>
        </p>

        <p>
            <label>Nilai Akhir (0–100):</label><br>
            <input type="number" name="NilaiAkhir"
                   min="0" max="100" step="0.01" required>
        </p>

        <p>
            <label>Foto Mahasiswa (opsional):</label><br>
            <input type="file" name="foto"
                   accept=".jpg,.jpeg,.png,.gif,.webp">
        </p>

        <button type="submit">Simpan Data</button>
    </form>
</body>
</html>
