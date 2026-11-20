<?php
// Endpoint penerima data dari ESP32 (Langkah 1: jarak ultrasonik)
date_default_timezone_set('Asia/Jakarta');   // jam WIB, sama dengan Laravel
header('Content-Type: text/plain');

// --- Konfigurasi database (lokal XAMPP) ---
// Saat dipindah ke hosting, ganti 4 baris ini dengan data hosting.
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "smart_fish_feeder";

mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    die("Koneksi database gagal");
}

// Hanya terima POST yang berisi 'jarak'
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['jarak'])) {
    http_response_code(400);
    die("Gagal: data jarak tidak ditemukan");
}

// Validasi: harus angka wajar (0 - 500 cm)
$jarak = filter_var($_POST['jarak'], FILTER_VALIDATE_FLOAT);
if ($jarak === false || $jarak < 0 || $jarak > 500) {
    http_response_code(400);
    die("Gagal: nilai jarak tidak valid");
}

$waktu = date('Y-m-d H:i:s');

// Prepared statement: aman dari SQL injection
$stmt = $conn->prepare("INSERT INTO sensor_data (jarak, waktu) VALUES (?, ?)");
$stmt->bind_param("ds", $jarak, $waktu);

if ($stmt->execute()) {
    echo "Data berhasil disimpan";
} else {
    http_response_code(500);
    echo "Error menyimpan data";
}

$stmt->close();
$conn->close();