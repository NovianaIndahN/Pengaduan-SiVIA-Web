<?php
session_start();

// Kunci halaman
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] != true) {
    echo "<script>alert('Anda harus login terlebih dahulu!'); window.location='login.php';</script>";
    exit;
}

include 'koneksi.php';

// Jika tombol kirim ditekan
if (isset($_POST['kirim'])) {
    $id_user = $_SESSION['id_user'];
    $tgl = date('Y-m-d'); 
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi_laporan']);
    
    // Proses Upload Foto
    $nama_foto = $_FILES['foto']['name'];
    $lokasi_sementara = $_FILES['foto']['tmp_name'];
    
    $foto_baru = time() . '_' . $nama_foto;
    $folder_tujuan = "uploads/" . $foto_baru;
    
    if (move_uploaded_file($lokasi_sementara, $folder_tujuan)) {
        $query = "INSERT INTO tb_pengaduan (id_user, tgl_pengaduan, judul, isi_laporan, foto, status) 
                  VALUES ('$id_user', '$tgl', '$judul', '$isi', '$foto_baru', 'menunggu')";
                  
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Laporan berhasil dikirim!'); window.location='dashboard.php';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan ke database!');</script>";
        }
    } else {
        echo "<script>alert('Gagal mengunggah foto!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan - SiVIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        :root {
            --warna-1: #001524;
            --warna-2: #0B3A42; 
            --warna-3: #15616D;
            --warna-4: #5C9A9C; 
            --warna-5: #E7D7C1; 
            --bg-body: #FFECD1; 
        }

        body { 
            background-color: #FFECD1;
            background-image:
                radial-gradient(rgba(0, 21, 36, 0.10) 1.4px, transparent 1.4px),
                radial-gradient(ellipse at 0% 0%, rgba(21, 97, 109, 0.35) 0, transparent 50%),
                radial-gradient(ellipse at 100% 45%, rgba(92, 154, 156, 0.40) 0, transparent 48%),
                linear-gradient(135deg, #FFF3DE 0%, #FFE3BC 55%, #D3E6E1 100%);
            background-size: 24px 24px, auto, auto, auto;
            background-attachment: fixed;
            font-family: 'Inter', sans-serif; 
            color: #333; 
            overflow-x: hidden;
        }

        .wrapper { display: flex; min-height: 100vh; position: relative; z-index: 1; }

        /* Dekorasi Background Aesthetic */
        body::before {
            content: "";
            position: fixed; inset: 0;
            pointer-events: none; z-index: 0;
            background:
                repeating-radial-gradient(circle at 100% 0%, transparent 0 46px, rgba(21, 97, 109, 0.16) 46px 48px),
                repeating-radial-gradient(circle at 0% 100%, transparent 0 60px, rgba(0, 21, 36, 0.08) 60px 62px);
            -webkit-mask-image: radial-gradient(circle at 100% 0%, #000 0, transparent 55%), radial-gradient(circle at 0% 100%, #000 0, transparent 50%);
            
            mask-image: radial-gradient(circle at 100% 0%, #000 0, transparent 55%), radial-gradient(circle at 0% 100%, #000 0, transparent 50%);
        }
        body::after {
            content: "";
            position: fixed; left: 0; bottom: 0;
            width: 100%; height: 42vh; min-height: 260px;
            pointer-events: none; z-index: 0;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 400' preserveAspectRatio='none'><path fill='%2315616D' fill-opacity='0.16' d='M0,220 C240,120 480,320 760,230 C1040,140 1240,260 1440,180 L1440,400 L0,400 Z'/><path fill='%235C9A9C' fill-opacity='0.22' d='M0,290 C260,200 520,350 800,290 C1080,230 1260,330 1440,270 L1440,400 L0,400 Z'/><path fill='%23001524' fill-opacity='0.10' d='M0,350 C300,290 560,390 860,345 C1120,305 1300,370 1440,335 L1440,400 L0,400 Z'/></svg>");
        }

        /* Sidebar Styling */
        .sidebar { 
            width: 260px; 
            min-width: 260px;
            background-color: var(--warna-1); 
            color: white; 
            transition: all 0.3s ease; 
            box-shadow: 4px 0 10px rgba(0,0,0,0.05); 
            z-index: 10; 
        }

        .sidebar.collapsed,
        .sidebar-collapsed .sidebar {
            width: 80px !important;
            min-width: 80px !important;
        }

        .sidebar-brand { 
            display: flex; 
            align-items: center; 
            padding: 20px 20px; 
            transition: all 0.3s ease;
        }

        .my-logo {
            width: 56px !important;
            height: 56px !important;
            min-width: 56px !important;
            min-height: 56px !important;
            object-fit: cover !important;
            border-radius: 14px !important;
            flex-shrink: 0 !important;
            transform: scale(1.85) !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2) !important;
            transition: all 0.3s ease !important;
        }

        .brand-text {
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .sidebar.collapsed .brand-text,
        .sidebar-collapsed .brand-text,
        body.sidebar-collapsed .brand-text {
            display: none !important;
        }

        .sidebar.collapsed .sidebar-brand,
        .sidebar-collapsed .sidebar-brand,
        body.sidebar-collapsed .sidebar-brand {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .sidebar a { 
            color: rgba(255,255,255,0.7); 
            text-decoration: none; 
            padding: 12px 20px !important; 
            display: flex !important; 
            align-items: center !important; 
            font-weight: 500; 
            border-radius: 0 30px 30px 0 !important; 
            margin-right: 15px !important; 
            margin-left: 0 !important;
            margin-bottom: 6px !important; 
            white-space: nowrap !important;
            transition: all 0.2s ease; 
        }

        .sidebar a:hover { 
            color: white; 
            background-color: rgba(255,255,255,0.08); 
        }

        .sidebar a.active { 
            background-color: var(--warna-3) !important; 
            color: white !important;
            font-weight: 600; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.15); 
        }

        .sidebar a i { 
            margin-right: 12px !important; 
            font-size: 1.2rem !important; 
            min-width: 24px;
            text-align: center;
        }

        .sidebar.collapsed a span,
        .sidebar-collapsed .sidebar a span {
            display: none !important;
        }

        .sidebar.collapsed a,
        .sidebar-collapsed .sidebar a {
            justify-content: center !important;
            border-radius: 14px !important;
            margin: 0 12px 6px 12px !important;
            padding: 12px 0 !important;
        }

        .sidebar.collapsed a i,
        .sidebar-collapsed .sidebar a i {
            margin-right: 0 !important;
        }

        /* Main Content */
        .main-content { flex-grow: 1; width: 100%; overflow-x: hidden; }
        .topbar { background-color: white; padding: 15px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center; }
        
        .form-card { 
            background: rgba(255, 255, 255, 0.95) !important; 
            border-radius: 20px !important; 
            padding: 30px; 
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            box-shadow: 0 12px 28px rgba(0, 21, 36, 0.10) !important; 
        }
        .btn-tema { background-color: var(--warna-3); color: white; border: none; padding: 10px 25px; font-weight: 500; border-radius: 8px; }
        .btn-tema:hover { background-color: var(--warna-1); color: white; }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav class="sidebar py-2" id="sidebar">
        <div class="sidebar-brand">
            <img src="logoweb.png" alt="Logo SiVIA" class="my-logo">
            <div class="brand-text ms-2">
                <h5 class="mb-0 fw-bold text-white">SiVIA</h5>
                <small class="text-white-50" style="font-size: 0.8rem;">Panel <?= ucfirst($_SESSION['role']); ?></small>
            </div>
        </div>
        
        <a href="dashboard.php">
            <i class="bi bi-grid"></i> 
            <span>Dashboard</span>
        </a>
        <a href="buat_laporan.php" class="active">
            <i class="bi bi-pencil-square"></i> 
            <span>Buat Laporan</span>
        </a>
        <a href="riwayat.php">
            <i class="bi bi-list-task"></i> 
            <span>Riwayat Laporanku</span>
        </a>

        <div class="mt-4"></div>
        <a href="logout.php">
            <i class="bi bi-box-arrow-right"></i> 
            <span>Logout</span>
        </a>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <header class="topbar">
            <div>
                <button class="btn btn-light" id="btnToggleSidebar"><i class="bi bi-list fs-5"></i></button>
            </div>
            <div class="d-flex align-items-center">
                <span class="me-3 text-secondary">Halo, <strong class="text-dark"><?= htmlspecialchars($_SESSION['nama']); ?></strong> <small>(<?= ucfirst($_SESSION['role']); ?>)</small></span>
                <i class="bi bi-person-circle fs-3" style="color: var(--warna-1);"></i>
            </div>
        </header>

        <main class="p-4 p-md-5">
            <div class="mb-4">
                <h3 class="fw-bold mb-1" style="color: var(--warna-1);">Tulis Laporan Baru</h3>
                <p class="text-secondary">Sampaikan keluhan fasilitas di lingkungan Anda.</p>
            </div>
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-card">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">Judul Laporan</label>
                                <input type="text" class="form-control" name="judul" placeholder="Contoh: Jalan Berlubang di RT 04" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">Isi Detail Laporan & Lokasi</label>
                                <textarea class="form-control" name="isi_laporan" rows="5" placeholder="Ceritakan secara detail kerusakan dan lokasi pastinya..." required></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-secondary">Unggah Foto Bukti</label>
                                <input class="form-control" type="file" name="foto" accept="image/*" required>
                                <small class="text-muted mt-1 d-block">Format file yang diizinkan: JPG, JPEG, PNG.</small>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <a href="dashboard.php" class="btn btn-light me-2">Batal</a>
                                <button type="submit" name="kirim" class="btn btn-tema"><i class="bi bi-send me-2"></i>Kirim Laporan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sidebar = document.getElementById("sidebar");
        const btnToggle = document.getElementById("btnToggleSidebar");

        if (btnToggle && sidebar) {
            btnToggle.addEventListener("click", function () {
                sidebar.classList.toggle("collapsed");
            });
        }
    });
</script>

</body>
</html>