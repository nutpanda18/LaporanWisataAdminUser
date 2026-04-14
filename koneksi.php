<?php
$host = "localhost";
$user = "root";      
$pass = "";          
$db   = "LaporanKeluhanWisata"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);


if (!$koneksi) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>