<?php
$servername = "localhost"; // Ganti dengan nama server Anda
$username = "root";     // Ganti dengan username Anda
$password = "rizkymaulana31";     // Ganti dengan password Anda
$dbname = "note-app";  // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
// echo "Koneksi berhasil";

// Tutup koneksi
// $conn->close();
?>
