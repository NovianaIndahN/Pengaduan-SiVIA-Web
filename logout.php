<?php
session_start();
session_unset();   // Mengosongkan semua data pengguna
session_destroy(); // Menghancurkan sesi agar tidak bisa kembali pakai tombol 'back' browser

// Memunculkan pop-up dan mengarahkan kembali ke halaman login
echo "<script>alert('Anda telah berhasil logout!'); window.location='login.php';</script>";
?>