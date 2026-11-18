<?php
// Konfigurasi Database (Sesuai parameter Anda)
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "smart_fish_feeder";

// Membuat koneksi ke MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Menangkap data dari ESP
if(isset($_POST['jarak'])) {
    $jarak_masuk = $_POST['jarak'];
    
    // Ganti 'sensor_data' dengan nama tabel asli yang Anda buat di database
    // Ganti 'jarak' dengan nama kolom di tabel Anda
    $sql = "INSERT INTO sensor_data (jarak, waktu) VALUES ('$jarak_masuk', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil disimpan";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Gagal: Data jarak tidak ditemukan";
}

$conn->close();
?>