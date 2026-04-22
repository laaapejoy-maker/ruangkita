<?php
include "koneksi.php";

$cin = $_POST['cin'] ?? '';
$cout = $_POST['cout'] ?? '';

if(!$cin || !$cout){
    exit;
}

$query = "SELECT * FROM bookings 
          WHERE ('$cin' < checkout) 
          AND ('$cout' > checkin)";

$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    echo "bentrok";
}else{
    echo "aman";
}
?>