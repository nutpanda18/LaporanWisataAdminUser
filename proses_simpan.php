<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: Login.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    $nama_pelapor = mysqli_real_escape_string($koneksi, $_POST['nama_pelapor']);
    $lokasi_wisata = mysqli_real_escape_string($koneksi, $_POST['lokasi_wisata']);
    $isi_laporan = mysqli_real_escape_string($koneksi, $_POST['isi_laporan']);
    $tanggal = date("Y-m-d H:i:s");
    $status = "Menunggu"; // 

    $query = "INSERT INTO laporan (nama_pelapor, lokasi_wisata, isi_laporan, tanggal_laporan, status) 
              VALUES ('$nama_pelapor', '$lokasi_wisata', '$isi_laporan', '$tanggal', '$status')";

    if (mysqli_query($koneksi, $query)) {
        
        header("Location: Home.php?pesan=sukses");
    } else {
      
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }
} else {
    
    header("Location: Home.php");
}
?>