<?php
session_start();

if(!isset($_SESSION['status_login']) || $_SESSION['status_login'] != true){
    echo "<scripts>alert('Ayoo login dulu yaa!'); window.location= 'login.php';<script";
    exit;
}

if ($_SESSION['role'] != 'teknisi') {
    echo "<script>alert('Kamu Gak Bisa Akses!!'); window.location='dashboard.php'</script>";
    exit;
}

include 'koneksi.php';

$query = "SELECT tb_pengaduan.*, tb_users.nama 
          FROM tb_pengaduan 
          JOIN tb_users ON tb_pengaduan.id_user = tb_users.id_user 
          ORDER BY FIELD(status, 'diproses', 'menunggu', 'selesai'), tgl_pengaduan DESC";
$q_tugas = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas Perbaikan - SiVIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        :root {
            --warna-1: #001524; /* Biru Dongker Gelap */
            --warna-2: #0B3A42; 
            --warna-3: #15616D; /* Hijau Ocean / Teal */
            --warna-4: #5C9A9C; 
            --warna-5: #E7D7C1; 
            --bg-body: #FFECD1; 
        }

        body { 
            background-color: var(--bg-body); 
            font-family: 'Inter', sans-serif; 
            color: #333; 
            overflow-x: hidden;
        }

        .wrapper { display: flex; min-height: 100vh; }
        
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

        /* Tampilan Sidebar saat Ditutup/Collapsed */
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

        /* Aturan Logo Gambar Presisi */
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

        /* Menyembunyikan Teks & Menengahkan Logo saat Minimize */
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

        /* Link Menu Sidebar - Persis Seperti Dashboard */
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

        /* Sembunyikan Teks Menu saat Minimize */
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

        /* Content Area & Table Styling (Asli Milikmu) */
        .main-content { flex-grow: 1; width: 100%; overflow-x: hidden; }
        .topbar { background-color: white; padding: 15px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center; }
        
        .table-container { background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .table thead th { border-bottom: 2px solid #edf2f9; color: #8898aa; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; padding-bottom: 15px; }
        .table tbody td { vertical-align: middle; padding: 15px 10px; border-bottom: 1px solid #edf2f9; font-size: 0.95rem; }
        .btn-detail { border: 1px solid #e2e8f0; background: transparent; color: #64748b; border-radius: 8px; font-size: 0.85rem; padding: 5px 15px; text-decoration: none; }
        .btn-detail:hover { background: var(--bg-body); color: var(--warna-1); }
        .badge-status { padding: 6px 12px; border-radius: 30px; font-weight: 500; font-size: 0.8rem; }
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
        <?php if ($_SESSION['role'] == 'teknisi') { ?>
            <a href="tugas.php" class="active">
                <i class="bi bi-tools"></i> 
                <span>Daftar Tugas Perbaikan</span>
            </a>
        <?php } ?>

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
                <span class="me-3 text-secondary">Halo, <strong class="text-dark"><?= $_SESSION['nama']; ?></strong> <small>(<?= ucfirst($_SESSION['role']); ?>)</small></span>
                <i class="bi bi-person-circle fs-3" style="color: var(--warna-1);"></i>
            </div>
        </header>

        <main class="p-4 p-md-5">
            <div class="mb-4">
                <h3 class="fw-bold mb-1" style="color: var(--warna-1);">Daftar Tugas Perbaikan</h3>
                <p class="text-secondary">Daftar laporan kerusakan fasilitas yang perlu segera ditangani.</p>
            </div>
            
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Lapor</th>
                                <th>Lokasi / Judul</th>
                                <th>Pelapor</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if(mysqli_num_rows($q_tugas) > 0) {
                                while($row = mysqli_fetch_assoc($q_tugas)) {
                                    if($row['status'] == 'menunggu') {
                                        $bg_color = 'var(--warna-1)';
                                    } elseif($row['status'] == 'diproses') {
                                        $bg_color = 'var(--warna-4)';
                                    } else {
                                        $bg_color = 'var(--warna-5)';
                                    }
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= date('d M Y', strtotime($row['tgl_pengaduan'])); ?></td>
                                <td class="fw-medium"><?= htmlspecialchars($row['judul']); ?></td>
                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                <td><span class="badge-status text-white" style="background-color: <?= $bg_color; ?>; text-transform: capitalize;"><?= $row['status']; ?></span></td>
                                <td><a href="detail.php?id=<?= $row['id_pengaduan']; ?>" class="btn-detail">Kerjakan</a></td>
                            </tr>
                            <?php 
                                } 
                            } else { 
                            ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-check-circle fs-1 d-block mb-3 text-success"></i>
                                    Semua tugas sudah selesai. Tidak ada laporan kerusakan saat ini.
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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