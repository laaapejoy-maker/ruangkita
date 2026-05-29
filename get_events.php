<?php
session_start();
include "koneksi.php";

$query = mysqli_query($conn, "SELECT ruangan_nama, checkin, checkout, status, nama FROM bookings WHERE status IN ('disetujui', 'pending')");

$events = [];

while ($row = mysqli_fetch_assoc($query)) {
    $color = '#2563eb'; // default
    if ($row['status'] == 'disetujui') {
        $color = '#dc2626'; // Merah untuk yang sudah disetujui
    } else if ($row['status'] == 'pending') {
        $color = '#f59e0b'; // Orange untuk yang pending
    }

    $events[] = [
        'title' => $row['ruangan_nama'] . ' (' . $row['status'] . ')',
        'start' => $row['checkin'],
        'end'   => $row['checkout'],
        'color' => $color
    ];
}

header('Content-Type: application/json');
echo json_encode($events);
?>
