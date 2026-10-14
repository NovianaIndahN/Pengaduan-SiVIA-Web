<?php
session_start();

// Kunci Halaman
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] != true) {
    echo "<script>alert('Anda harus login terlebih dahulu!'); window.location='login.php';</script>";
    exit;
}

// Pastikan hanya Admin yang bisa mencetak
if ($_SESSION['role'] != 'admin') {
    echo "<script>alert('Anda tidak memiliki akses!'); window.location='dashboard.php';</script>";
    exit;
}

include 'koneksi.php';

// Ambil semua data laporan dari database beserta nama pelapornya
$query = "SELECT tb_pengaduan.*, tb_users.nama 
          FROM tb_pengaduan 
          JOIN tb_users ON tb_pengaduan.id_user = tb_users.id_user 
          ORDER BY tgl_pengaduan DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Pengaduan - SiVIA</title>

    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Times New Roman', Times, serif; /* Font standar dokumen resmi */
            color: black;
            background-color: white;
            padding: 30px;
        }

        /* Kop Surat Resmi */
        .kop-surat {
            text-align: center;
            border-bottom: 3px double black;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }

        .kop-surat h2 {
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 4px;
            font-size: 1.8rem;
        }

        .kop-surat p {
            font-size: 0.95rem;
            margin-bottom: 2px;
        }

        /* Styling Tabel */
        .table th {
            background-color: #f2f2f2 !important; 
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact;
            vertical-align: middle;
            font-size: 0.95rem;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        /* Menyembunyikan elemen tombol navigasi saat diprint */
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Navigasi Manual (Hanya terlihat di layar) -->
    <div class="mb-4 no-print text-end">
        <a href="dashboard.php" class="btn btn-secondary me-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer-fill"></i> Cetak Dokumen
        </button>
    </div>

    <!-- Kop Surat Buatan -->
    <div class="kop-surat">
        <h2>SISTEM PENGADUAN LAYANAN WARGA (SiVIA)</h2>
        <p>Alamat: Jl. Raya Desa Makmur No. 123, Kode Pos 54321</p>
        <p>Website: www.sivia-desa.go.id | Email: kontak@sivia.go.id</p>
    </div>

    <h4 class="text-center fw-bold mb-4" style="letter-spacing: 0.5px;">REKAPITULASI DATA PENGADUAN WARGA</h4>

    <!-- Tabel Data Laporan -->
    <table class="table table-bordered border-dark">
        <thead>
            <tr class="text-center">
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Nama Pelapor</th>
                <th width="40%">Judul Laporan</th>
                <th width="20%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td class="text-center"><?= $no++; ?></td>
                <td class="text-center"><?= date('d M Y', strtotime($row['tgl_pengaduan'])); ?></td>
                <td><?= htmlspecialchars($row['nama']); ?></td>
                <td><?= htmlspecialchars($row['judul']); ?></td>
                <td class="text-center text-capitalize"><strong><?= $row['status']; ?></strong></td>
            </tr>
            <?php 
                } 
            } else { 
            ?>
            <tr>
                <td colspan="5" class="text-center py-4 text-muted">Belum ada data pengaduan warga.</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Bagian Tanda Tangan Admin -->
    <div class="mt-5 text-end float-end" style="width: 250px;">
        <p class="mb-1">Dicetak pada: <strong><?= date('d M Y'); ?></strong></p>
        <p class="mb-5">Admin Pengelola SiVIA,</p>
        <br><br>
        <p class="fw-bold mb-0">( <?= htmlspecialchars($_SESSION['nama'] ?? 'Admin SiVIA'); ?> )</p>
    </div>

    <!-- Otomatis memunculkan jendela print saat halaman dibuka -->
    <script>
        window.print();
    </script>

</body>
</html>