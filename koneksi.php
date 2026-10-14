<?php
$host = "localhost";
$user = "root";       // user mysql php
$pass = "";           
$db   = "db_pengaduan"; // nama databseku

$conn = mysqli_connect($host,$user, $pass,$db);

// ngecek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>