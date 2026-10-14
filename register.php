<?php
session_start();
include 'koneksi.php';

$error_msg = "";
$success_msg = "";

// Jika tombol daftar ditekan
if (isset($_POST['btn_register'])) {
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $telp = mysqli_real_escape_string($conn, $_POST['telp']); // Tambahan variabel telepon
    $password = md5($_POST['password']); 
    $role = 'warga'; // Otomatis mendaftar sebagai warga

    // Cek apakah NIK sudah pernah didaftarkan sebelumnya
    $cek_nik = mysqli_query($conn, "SELECT * FROM tb_users WHERE nik='$nik'");
    
    if (mysqli_num_rows($cek_nik) > 0) {
        $error_msg = "NIK tersebut sudah terdaftar! Silakan langsung login.";
    } else {
        // Simpan data ke database (Tambahan kolom telp)
        $insert = mysqli_query($conn, "INSERT INTO tb_users (nik, nama, telp, password, role) VALUES ('$nik', '$nama', '$telp', '$password', '$role')");
        
        if ($insert) {
            $success_msg = "Pendaftaran berhasil!";
        } else {
            $error_msg = "Terjadi kesalahan sistem, gagal mendaftar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Portal VIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        
        :root {
            --navy: #001524;
            --teal: #15616D;
            --cream: #FFECD1;
            --bg-body: #F4F7F6;
        }
        
        body {
            background-color: var(--bg-body);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 21, 36, 0.15);
            overflow: hidden;
            width: 100%;
            max-width: 950px;
            display: flex;
            flex-wrap: wrap;
            position: relative;
        }

        .login-left {
            background-color: var(--navy);
            color: var(--cream);
            padding: 60px 50px;
            width: 50%;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden; 
        }

        .deco-circle-1 { position: absolute; width: 300px; height: 300px; background: rgba(255, 236, 209, 0.05); border-radius: 50%; top: -120px; left: -120px; pointer-events: none; z-index: 0; }
        .deco-circle-2 { position: absolute; width: 220px; height: 220px; background: rgba(21, 97, 109, 0.25); border-radius: 50%; bottom: -60px; right: 0px; pointer-events: none; z-index: 0; }

        .brand-title { font-size: 3rem; font-weight: 800; margin-bottom: 5px; z-index: 1; }
        .brand-subtitle { font-size: 1rem; opacity: 0.8; font-weight: 400; line-height: 1.6; z-index: 1; }

        .illustration-svg { margin: 20px auto 0 auto; z-index: 1; position: relative; filter: drop-shadow(0 15px 25px rgba(0,0,0,0.3)); animation: float 4s ease-in-out infinite; }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-15px); } 100% { transform: translateY(0px); } }

        .wave-divider { position: absolute; right: -1px; top: 0; height: 100%; width: 80px; z-index: 2; }

        .login-right { width: 50%; padding: 40px 50px; background: white; display: flex; flex-direction: column; justify-content: center; position: relative; }
        .form-title { color: var(--teal); font-weight: 700; font-size: 1.8rem; margin-bottom: 5px; text-align: center; }
        .form-subtitle { color: #888; text-align: center; margin-bottom: 25px; font-size: 0.9rem; }

        .input-group { background-color: #f4f7f6; border-radius: 50px; padding: 3px 10px; margin-bottom: 15px; border: 2px solid transparent; transition: all 0.3s; }
        .input-group:focus-within { border-color: var(--teal); background-color: white; box-shadow: 0 4px 15px rgba(21, 97, 109, 0.1); }
        .input-group-text { background: transparent; border: none; color: var(--teal); font-size: 1.1rem; padding-left: 15px; }
        .form-control { background: transparent; border: none; box-shadow: none !important; padding: 10px 15px; font-size: 0.95rem; color: var(--navy); }
        
        input[type="number"]::-webkit-inner-spin-button, input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type="number"] { -moz-appearance: textfield; }
        
        .btn-tema { background-color: var(--teal); color: var(--cream); border: none; border-radius: 50px; padding: 12px; font-weight: 600; font-size: 1rem; margin-top: 10px; transition: 0.3s; box-shadow: 0 8px 20px rgba(21, 97, 109, 0.3); }
        .btn-tema:hover { background-color: var(--navy); color: white; transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0, 21, 36, 0.3); }

        @media (max-width: 768px) {
            .login-left, .login-right { width: 100%; padding: 40px 30px; }
            .login-left { text-align: center; border-radius: 24px 24px 0 0; }
            .illustration-svg { display: none; }
            .wave-divider, .deco-circle-1, .deco-circle-2 { display: none; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <!-- Kiri: Branding VIA -->
    <div class="login-left">
        <div class="deco-circle-1"></div>
        <div class="deco-circle-2"></div>
        <h1 class="brand-title">Portal VIA</h1>
        <p class="brand-subtitle">Bergabunglah bersama kami. Sampaikan aspirasi Anda untuk lingkungan yang lebih baik.</p>
        <img src="kartunwebpns.png" alt="Ilustrasi Portal VIA" class="illustration-svg" style="width: 250px; max-width: 100%;">
        <svg class="wave-divider" viewBox="0 0 100 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path fill="#ffffff" d="M100,0 L70,0 C 100,30 40,70 70,100 L100,100 Z" />
        </svg>
    </div>

    <!-- Kanan: Form Register -->
    <div class="login-right">
        <h2 class="form-title">DAFTAR AKUN</h2>
        <p class="form-subtitle">Lengkapi data diri Anda di bawah ini</p>

        <form method="POST" action="">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-vcard-fill"></i></span>
                <input type="number" class="form-control" name="nik" placeholder="Nomor Induk Kependudukan (NIK)" required autofocus>
            </div>
            
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" required>
            </div>

            <!-- Tambahan Input Nomor Telepon -->
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                <input type="number" class="form-control" name="telp" placeholder="Nomor Telepon / WhatsApp" required>
            </div>
            
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control" name="password" placeholder="Buat Kata Sandi" required>
            </div>

            <button type="submit" name="btn_register" class="btn btn-tema w-100 mb-3">Daftar Sekarang</button>
            
            <div class="text-center mt-2" style="font-size: 0.9rem;">
                <span class="text-secondary">Sudah punya akun? </span>
                <a href="login.php" class="text-decoration-none fw-bold" style="color: var(--teal);">Masuk di sini</a>
            </div>
        </form>
    </div>
</div>

<!-- Logika Notifikasi SweetAlert2 -->
<?php if ($error_msg != ""): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: '<?= $error_msg ?>',
            confirmButtonColor: '#001524'
        });
    });
</script>
<?php endif; ?>

<?php if ($success_msg != ""): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= $success_msg ?>',
            showConfirmButton: false,
            timer: 2000,
            background: '#F4F7F6',
            color: '#001524'
        }).then(function() {
            window.location = 'login.php';
        });
    });
</script>
<?php endif; ?>

</body>
</html>