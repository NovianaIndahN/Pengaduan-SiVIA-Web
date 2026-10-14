<?php
session_start();

if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] != true) {
    echo "<script>alert('Ayoo Login Dulu Yaa!'); window.location='login.php';</script>";
    exit;
}

include 'koneksi.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location='dashboard.php';</script>";
    exit;
}

$id_pengaduan = $_GET['id'];
$id_user_login = $_SESSION['id_user']; // ID kita yang sedang login

// Proses Update Status (Khusus Admin/Teknisi)
if (isset($_POST['btn_ubah_status'])) {
    $status_baru = $_POST['status'];
    mysqli_query($conn, "UPDATE tb_pengaduan SET status='$status_baru' WHERE id_pengaduan='$id_pengaduan'");
    echo "<script>alert('Status berhasil diperbarui!'); window.location='detail.php?id=$id_pengaduan';</script>";
}

// Proses Kirim Tanggapan (Chat)
if (isset($_POST['btn_kirim_tanggapan'])) {
    $isi_tanggapan = mysqli_real_escape_string($conn, $_POST['isi_tanggapan']);
    $tgl_tanggapan = date('Y-m-d'); // Menyimpan tanggal saat ini
    
    $q_kirim = "INSERT INTO tb_tanggapan (id_pengaduan, id_user, tgl_tanggapan, isi_tanggapan) 
                VALUES ('$id_pengaduan', '$id_user_login', '$tgl_tanggapan', '$isi_tanggapan')";
    mysqli_query($conn, $q_kirim);
    
    // Refresh halaman agar chat baru muncul
    echo "<script>window.location='detail.php?id=$id_pengaduan';</script>";
}

// Ambil Detail Laporan Utama
$q_laporan = "SELECT tb_pengaduan.*, tb_users.nama FROM tb_pengaduan 
              JOIN tb_users ON tb_pengaduan.id_user = tb_users.id_user 
              WHERE id_pengaduan = '$id_pengaduan'";
$result_laporan = mysqli_query($conn, $q_laporan);

if (mysqli_num_rows($result_laporan) == 0) {
    echo "<script>alert('Laporan tidak ditemukan!'); window.location='dashboard.php';</script>";
    exit;
}

$data = mysqli_fetch_assoc($result_laporan);

// Menentukan warna badge status
if ($data['status'] == 'menunggu') { $bg_color = 'var(--warna-1)'; } 
elseif ($data['status'] == 'diproses') { $bg_color = 'var(--warna-4)'; } 
else { $bg_color = 'var(--warna-5)'; }

// Ambil Data Tanggapan (Riwayat Chat)
$q_chat = "SELECT tb_tanggapan.*, tb_users.nama, tb_users.role 
           FROM tb_tanggapan 
           JOIN tb_users ON tb_tanggapan.id_user = tb_users.id_user 
           WHERE id_pengaduan = '$id_pengaduan' 
           ORDER BY id_tanggapan ASC";
$result_chat = mysqli_query($conn, $q_chat);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - SiVIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
       :root {
            --warna-1: #001524; /* Biru Dongker Gelap untuk Sidebar & Teks Utama */
            --warna-2: #0B3A42; /* Warna transisi/hover */
            --warna-3: #15616D; /* Hijau Ocean untuk Tombol & Menu Aktif */
            --warna-4: #5C9A9C; /* Warna tosca muda untuk Status 'Diproses' */
            --warna-5: #E7D7C1; /* Krem Terang untuk Kotak Logo & Aksen */
            --bg-body: #FFECD1; /* Latar belakang abu-abu sangat muda agar bersih */
        }
        body { background-color: var(--bg-body); font-family: 'Inter', sans-serif; color: #333; }
        .wrapper { display: flex; min-height: 100vh; }
        
        .sidebar { width: 260px; background-color: var(--warna-1); color: white; transition: all 0.3s; box-shadow: 4px 0 10px rgba(0,0,0,0.05); z-index: 10; }
        .sidebar-brand { display: flex; align-items: center; padding: 20px 25px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; }
        .logo-box { background-color: var(--warna-5); color: var(--warna-1); width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-weight: bold; margin-right: 15px; }
        .sidebar a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 12px 25px; display: flex; align-items: center; font-weight: 500; border-radius: 0 25px 25px 0; margin-right: 15px; margin-bottom: 5px; transition: 0.2s; }
        .sidebar a:hover { color: white; background-color: rgba(255,255,255,0.1); }
        .sidebar a.active { background-color: var(--warna-3); box-shadow: 0 4px 6px rgba(0,0,0,0.1); color: white;}
        .sidebar a i { margin-right: 12px; font-size: 1.1rem; }
        
        .main-content { flex-grow: 1; width: 100%; overflow-x: hidden; }
        .topbar { background-color: white; padding: 15px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center; }
        
        .detail-card { background: white; border-radius: 16px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 20px; }
        .foto-bukti { width: 100%; max-height: 400px; object-fit: cover; border-radius: 12px; border: 1px solid #eee; }
        .badge-status { padding: 8px 16px; border-radius: 30px; font-weight: 500; font-size: 0.9rem; }
        .btn-tema { background-color: var(--warna-3); color: white; border: none; font-weight: 500; padding: 10px; border-radius: 8px;}
        .btn-tema:hover { background-color: var(--warna-1); color: white; }

        /* CSS Bubble Chat */
        .chat-container { max-height: 400px; overflow-y: auto; padding: 15px; background-color: #f8f9fa; border-radius: 12px; margin-bottom: 15px; border: 1px solid #e2e8f0; }
        .chat-bubble { max-width: 80%; padding: 12px 16px; border-radius: 16px; margin-bottom: 15px; position: relative; font-size: 0.95rem; line-height: 1.5; clear: both;}
        
        /* Chat dari Diri Sendiri (Kanan) */
        .chat-me { float: right; background-color: var(--warna-3); color: white; border-bottom-right-radius: 4px; }
        .chat-me .chat-time { color: rgba(255, 255, 255, 0.7); font-size: 0.75rem; text-align: right; display: block; margin-top: 5px; }
        
        /* Chat dari Orang Lain (Kiri) */
        .chat-other { float: left; background-color: #e9ecef; color: #333; border-bottom-left-radius: 4px; }
        .chat-other .chat-name { font-weight: 700; font-size: 0.8rem; color: var(--warna-1); margin-bottom: 3px; display: block; text-transform: capitalize;}
        .chat-other .chat-time { color: #888; font-size: 0.75rem; display: block; margin-top: 5px; }
        
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav class="sidebar py-2">
        <div class="sidebar-brand">
            <div class="logo-box">SV</div>
            <div>
                <h5 class="mb-0 fw-bold">SiVIA</h5>
                <small style="opacity: 0.7; font-size: 0.8rem;">Panel <?= ucfirst($_SESSION['role']); ?></small>
            </div>
        </div>
        
        <a href="dashboard.php" class="active"><i class="bi bi-grid"></i> Dashboard</a>
        <?php if ($_SESSION['role'] == 'warga') { ?>
            <a href="buat_laporan.php"><i class="bi bi-pencil-square"></i> Buat Laporan</a>
            <a href="riwayat.php"><i class="bi bi-list-task"></i> Riwayat Laporanku</a>
        <?php } elseif ($_SESSION['role'] == 'admin') { ?>
            <a href="verifikasi.php"><i class="bi bi-shield-check"></i> Verifikasi Laporan</a>
            <a href="kelola_pengguna.php"><i class="bi bi-people"></i> Kelola Pengguna</a>
            <a href="cetak.php"><i class="bi bi-printer"></i> Cetak Laporan</a>
        <?php } elseif ($_SESSION['role'] == 'teknisi') { ?>
            <a href="tugas.php"><i class="bi bi-tools"></i> Daftar Tugas Perbaikan</a>
        <?php } ?>
        <div class="mt-4"></div>
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <header class="topbar">
            <div><button class="btn btn-light d-md-none"><i class="bi bi-list"></i></button></div>
            <div class="d-flex align-items-center">
                <span class="me-3 text-secondary">Halo, <strong class="text-dark"><?= $_SESSION['nama']; ?></strong> <small>(<?= ucfirst($_SESSION['role']); ?>)</small></span>
                <i class="bi bi-person-circle fs-3" style="color: var(--warna-1);"></i>
            </div>
        </header>

        <main class="p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="color: var(--warna-1);">Detail Laporan</h3>
                    <p class="text-secondary">Informasi lengkap terkait pengaduan warga.</p>
                </div>
                <a href="dashboard.php" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
            
            <div class="row">
                <!-- Kolom Kiri: Detail Pengaduan -->
                <div class="col-lg-8">
                    <div class="detail-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h4 class="fw-bold"><?= htmlspecialchars($data['judul']); ?></h4>
                            <span class="badge-status text-white" style="background-color: <?= $bg_color; ?>; text-transform: capitalize;">
                                <?= $data['status']; ?>
                            </span>
                        </div>
                        
                        <div class="text-muted mb-4 fs-6">
                            <i class="bi bi-calendar-event me-2"></i><?= date('d M Y', strtotime($data['tgl_pengaduan'])); ?>
                            <span class="mx-2">|</span>
                            <i class="bi bi-person me-2"></i>Oleh: <strong class="text-dark"><?= htmlspecialchars($data['nama']); ?></strong>
                        </div>

                        <?php if(!empty($data['foto'])): ?>
                            <img src="uploads/<?= $data['foto']; ?>" alt="Foto Bukti" class="foto-bukti mb-4">
                        <?php endif; ?>

                        <h6 class="fw-bold text-secondary">Isi Laporan:</h6>
                        <p style="line-height: 1.8; text-align: justify;">
                            <?= nl2br(htmlspecialchars($data['isi_laporan'])); ?>
                        </p>
                    </div>

                    <!-- Area Diskusi / Bubble Chat -->
                    <div class="detail-card">
                        <h5 class="fw-bold mb-4"><i class="bi bi-chat-dots-fill me-2" style="color: var(--warna-3);"></i>Ruang Diskusi</h5>
                        
                        <!-- Kotak Chat History -->
                        <div class="chat-container clearfix">
                            <?php 
                            if(mysqli_num_rows($result_chat) > 0) {
                                while($chat = mysqli_fetch_assoc($result_chat)) {
                                    // Cek apakah pesan ini milik user yang sedang login
                                    $is_me = ($chat['id_user'] == $id_user_login);
                            ?>
                                <div class="chat-bubble <?= $is_me ? 'chat-me' : 'chat-other'; ?>">
                                    <?php if(!$is_me): ?>
                                        <span class="chat-name"><?= htmlspecialchars($chat['nama']); ?> (<?= $chat['role']; ?>)</span>
                                    <?php endif; ?>
                                    
                                    <?= nl2br(htmlspecialchars($chat['isi_tanggapan'])); ?>
                                    
                                    <span class="chat-time"><?= date('d M Y', strtotime($chat['tgl_tanggapan'])); ?></span>
                                </div>
                            <?php 
                                }
                            } else {
                                // INI BAGIAN YANG DIPERBAIKI (Menggunakan kutip tunggal)
                                echo '<div class="text-center text-muted my-4 small">Belum ada tanggapan. Silakan mulai diskusi.</div>';
                            }
                            ?>
                        </div>

                        <!-- Form Ketik Pesan -->
                        <form method="POST" action="">
                            <div class="input-group">
                                <input type="text" class="form-control" name="isi_tanggapan" placeholder="Ketik tanggapan Anda di sini..." required autocomplete="off">
                                <button class="btn btn-tema px-4" type="submit" name="btn_kirim_tanggapan">
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Kolom Kanan: Update Status (Hanya Admin & Teknisi) -->
                <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'teknisi'): ?>
                <div class="col-lg-4">
                    <div class="detail-card" style="border-top: 4px solid var(--warna-3);">
                        <h5 class="fw-bold mb-3"><i class="bi bi-gear-fill me-2" style="color: var(--warna-3);"></i>Tindakan</h5>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small">Ubah Status Laporan</label>
                                <select class="form-select" name="status" required>
                                    <option value="menunggu" <?= ($data['status'] == 'menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                                    <option value="diproses" <?= ($data['status'] == 'diproses') ? 'selected' : ''; ?>>Diproses</option>
                                    <option value="selesai" <?= ($data['status'] == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                                </select>
                            </div>
                            <button type="submit" name="btn_ubah_status" class="btn btn-tema w-100">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
                
            </div>
        </main>
    </div>
</div>

<!-- Scroll otomatis ke chat terbawah saat halaman dimuat -->
<script>
    const chatContainer = document.querySelector('.chat-container');
    if(chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
</script>

</body>
</html>