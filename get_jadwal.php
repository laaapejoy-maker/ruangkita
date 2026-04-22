<?php
include "koneksi.php";

if($_SERVER["REQUEST_METHOD"] !== "POST"){
    exit;
}

$nama = $_POST['nama'] ?? '';
$prodi = $_POST['prodi'] ?? '';
$email = $_POST['email'] ?? '';

$cin_date = $_POST['cin_date'] ?? '';
$cin_time = $_POST['cin_time'] ?? '';
$cout_date = $_POST['cout_date'] ?? '';
$cout_time = $_POST['cout_time'] ?? '';

if(!$nama || !$prodi || !$email || !$cin_date || !$cin_time || !$cout_date || !$cout_time){
    echo "kosong";
    exit;
}

$checkin = $cin_date . " " . $cin_time;
$checkout = $cout_date . " " . $cout_time;

/* VALIDASI BENTROK */
$query = "SELECT * FROM bookings 
          WHERE ('$checkin' < checkout) 
          AND ('$checkout' > checkin)";

$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    echo "bentrok";
} else {
    mysqli_query($conn,"INSERT INTO bookings(nama,prodi,email,checkin,checkout)
                        VALUES('$nama','$prodi','$email','$checkin','$checkout')");
    echo "sukses";
}
?>