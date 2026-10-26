
<?php
require_once 'connect.php';

// Ambil pilihan filter
$cari = trim($_GET['cari'] ?? '');
$predikat = $_GET['predikat'] ?? '';
$urutan = $_GET['urutan'] ?? 'tinggi';
$per_halaman = (int)($_GET['per_halaman'] ?? 10);
$halaman = max(1, (int)($_GET['halaman'] ?? 1));

// Batasi pilihan jumlah data
if (!in_array($per_halaman, [10, 25, 50])) {
    $per_halaman = 10;
}

// Pengurutan nilai
$orderBy = ($urutan === 'rendah')
    ? 'NilaiAkhir ASC'
    : 'NilaiAkhir DESC';

// Filter pencarian
$cari_sql = mysqli_real_escape_string($conn, $cari);
$predikat_sql = mysqli_real_escape_string($conn, $predikat);

$where = " WHERE 1=1 ";

if ($cari !== '') {
    $where .= " AND (NIM LIKE '%$cari_sql%'
                 OR NamaMahasiswa LIKE '%$cari_sql%')";
}

if ($predikat !== '') {
    $where .= " AND Predikat = '$predikat_sql'";
}

// Hitung total data
$sqlCount = "SELECT COUNT(*) AS total
             FROM nilaimahasiswa $where";
$countResult = mysqli_query($conn, $sqlCount);
$totalData = (int)mysqli_fetch_assoc($countResult)['total'];

$totalHalaman = max(1, (int)ceil($totalData / $per_halaman));
$halaman = min($halaman, $totalHalaman);
$offset = ($halaman - 1) * $per_halaman;

// Ambil data sesuai halaman
$sql = "SELECT * FROM nilaimahasiswa
        $where
        ORDER BY $orderBy
        LIMIT $per_halaman OFFSET $offset";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Gagal mengambil data: " . mysqli_error($conn));
}

// Pilihan predikat berdasarkan data yang tersimpan
$sqlPredikat = "SELECT DISTINCT Predikat
                FROM nilaimahasiswa
                WHERE Predikat <> ''
                ORDER BY Predikat ASC";
$resultPredikat = mysqli_query($conn, $sqlPredikat);

// Buat link halaman dengan filter tetap tersimpan
function linkHalaman($nomor) {
    $params = $_GET;
    $params['halaman'] = $nomor;
    return '?' . http_build_query($params);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rekap Nilai Mahasiswa</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 25px;
    font-family: Arial, sans-serif;
    color: #1f2937;
    background: #f4f7fb;
}

h1 {
    font-size: 28px;
    margin: 10px 0 28px;
}

.panel {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 7px #0000000b;
}

.filter {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
    padding: 22px;
    border-bottom: 1px solid #e5e7eb;
}

.filter-title {
    margin-right: auto;
    font-size: 17px;
    font-weight: bold;
}

input, select, .btn {
    min-height: 42px;
    padding: 10px 13px;
    border: 1px solid #dbe1e8;
    border-radius: 7px;
    background: white;
    font-size: 14px;
}

input {
    width: 210px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    text-decoration: none;
    cursor: pointer;
    color: white;
    border: none;
}

.btn-filter {
    background: #6b7280;
}

.btn-add {
    background: #1670f8;
}

.table-wrap {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    white-space: nowrap;
}

th, td {
    padding: 18px 16px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
    font-size: 14px;
}

th {
    color: #111827;
    background: #fff;
}

tbody tr:hover {
    background: #f8fafc;
}

.foto {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #e5e7eb;
}

.no-foto {
    display: inline-flex;
    width: 58px;
    height: 58px;
    border-radius: 50%;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    color: #64748b;
    font-size: 11px;
}

.aksi {
    display: flex;
    align-items: center;
    gap: 7px;
}

.icon-btn {
    width: 42px;
    height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    background: white;
    cursor: pointer;
    text-decoration: none;
}

.icon-btn svg {
    width: 19px;
    height: 19px;
}

.edit {
    color: #1670f8;
    border: 1px solid #bfdbfe;
}

.delete {
    color: #ef4444;
    border: 1px solid #fecaca;
}

.footer-table {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    padding: 20px;
    color: #64748b;
    font-size: 14px;
}

.pagination {
    display: flex;
    gap: 6px;
}

.pagination a, .pagination span {
    min-width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    text-decoration: none;
    color: #374151;
    background: white;
}

.pagination .active {
    background: #1670f8;
    color: white;
    border-color: #1670f8;
}

.empty {
    text-align: center;
    padding: 30px;
    color: #64748b;
}

.copyright {
    text-align: center;
    color: #64748b;
    font-size: 13px;
    margin: 35px 0 5px;
}

@media (max-width: 700px) {
    body {
        padding: 12px;
    }

    h1 {
        font-size: 23px;
    }

    .filter-title {
        width: 100%;
    }

    input, select {
        width: 100%;
    }
}
</style>
</head>

<body>

<h1>🎓 Rekap Nilai Mahasiswa</h1>

<div class="panel">

    <form method="GET" action="" class="filter">
        <div class="filter-title">Directory Nilai</div>

        <input
            type="text"
            name="cari"
            placeholder="🔍 Cari Nama atau NIM..."
            value="<?= htmlspecialchars($cari) ?>"
        >

        <select name="predikat">
            <option value="">Semua Predikat</option>
            <?php while ($p = mysqli_fetch_assoc($resultPredikat)) : ?>
                <option
                    value="<?= htmlspecialchars($p['Predikat']) ?>"
                    <?= $predikat === $p['Predikat'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($p['Predikat']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="urutan">
            <option value="tinggi" <?= $urutan === 'tinggi' ? 'selected' : '' ?>>
                Nilai: Tinggi - Rendah
            </option>
            <option value="rendah" <?= $urutan === 'rendah' ? 'selected' : '' ?>>
                Nilai: Rendah - Tinggi
            </option>
        </select>

        <select name="per_halaman">
            <?php foreach ([10, 25, 50] as $jumlah) : ?>
                <option value="<?= $jumlah ?>"
                    <?= $per_halaman === $jumlah ? 'selected' : '' ?>>
                    <?= $jumlah ?> / halaman
                </option>
            <?php endforeach; ?>
        </select>

        <button class="btn btn-filter" type="submit">
            ⚑ Terapkan
        </button>

        <a href="formnilai.php" class="btn btn-add">
            + Tambah Data
        </a>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Foto</th>
                    <th>Nilai Akhir</th>
                    <th>Nilai Huruf</th>
                    <th>Predikat</th>
                    <th>Created Date</th>
                    <th>Modified Date</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php if (mysqli_num_rows($result) > 0) : ?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['NIM']) ?></td>
                    <td><?= htmlspecialchars($row['NamaMahasiswa']) ?></td>

                    <td>
                        <?php if (!empty($row['foto'])) : ?>
                            <img
                                class="foto"
                                src="uploads/<?= rawurlencode(basename($row['foto'])) ?>"
                                alt="Foto mahasiswa"
                            >
                        <?php else : ?>
                            <span class="no-foto">No Foto</span>
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($row['NilaiAkhir']) ?></td>
                    <td><?= htmlspecialchars($row['NilaiHuruf']) ?></td>
                    <td><?= htmlspecialchars($row['Predikat']) ?></td>

                    <td><?= !empty($row['created_date'])
                        ? htmlspecialchars($row['created_date']) : '-' ?></td>

                    <td><?= !empty($row['modified_date'])
                        ? htmlspecialchars($row['modified_date']) : '-' ?></td>

                    <td>
                        <div class="aksi">
                            <a class="icon-btn edit"
                               href="edit_form.php?NIM=<?= urlencode($row['NIM']) ?>"
                               title="Edit data"
                               aria-label="Edit data">
                                <svg viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"/>
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                                </svg>
                            </a>

                            <form action="hapusnilai.php" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                <input type="hidden" name="NIM"
                                       value="<?= htmlspecialchars($row['NIM']) ?>">

                                <button class="icon-btn delete" type="submit"
                                        title="Hapus data" aria-label="Hapus data">
                                    <svg viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 6h18"/>
                                        <path d="M8 6V4h8v2"/>
                                        <path d="M19 6l-1 14H6L5 6"/>
                                        <path d="M10 11v5M14 11v5"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="9" class="empty">
                        Data mahasiswa tidak ditemukan.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="footer-table">
        <div>
            Menampilkan
            <?= $totalData > 0 ? $offset + 1 : 0 ?>
            - <?= min($offset + $per_halaman, $totalData) ?>
            dari <?= $totalData ?> entri
        </div>

        <div class="pagination">
            <?php if ($halaman > 1) : ?>
                <a href="<?= htmlspecialchars(linkHalaman($halaman - 1)) ?>">‹</a>
            <?php else : ?>
                <span>‹</span>
            <?php endif; ?>

            <span class="active"><?= $halaman ?></span>

            <?php if ($halaman < $totalHalaman) : ?>
                <a href="<?= htmlspecialchars(linkHalaman($halaman + 1)) ?>">›</a>
            <?php else : ?>
                <span>›</span>
            <?php endif; ?>
        </div>
    </div>

</div>

<div class="copyright">
    © <?= date('Y') ?> Rekap Nilai Mahasiswa. All rights reserved.
</div>

</body>
</html>

<?php
mysqli_free_result($result);
mysqli_close($conn);
?>
