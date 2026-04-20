<?php
include 'koneksi.php';
$id = $_GET['id'];

$query = "DELETE FROM laporan WHERE id_laporan = '$id'"; 
$hasil = mysqli_query($koneksi, $query);

if($hasil) {
    header("location:Home.php");
} else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}
?>