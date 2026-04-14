<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: Home.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];


    $query = "UPDATE laporan SET status = '$status' WHERE id_laporan = '$id'";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: Home.php?status=updated");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>