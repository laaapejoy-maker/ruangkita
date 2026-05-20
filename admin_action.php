<?php
session_start();

header('Content-Type: application/json');

include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Harus login"
    ]);
    exit;
}

if ($_SESSION['role'] != 'admin') {
    echo json_encode([
        "status" => "error",
        "message" => "Bukan admin"
    ]);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {

   
    case 'get_stats':

        $ruangan = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT COUNT(*) as total FROM ruangan")
        );

        $booking = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings")
        );

        $pending = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status='pending'")
        );

        $disetujui = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status='disetujui'")
        );

        echo json_encode([
            "status" => "success",
            "data" => [
                "total_ruangan" => $ruangan['total'],
                "booking_hari_ini" => $booking['total'],
                "pending" => $pending['total'],
                "disetujui" => $disetujui['total']
            ]
        ]);

    break;

    
    case 'get_bookings':

        $type = $_GET['type'] ?? 'all';

        $where = "";

        if ($type == "pending") {
            $where = "WHERE status='pending'";
        }

        $query = mysqli_query($conn, "
            SELECT *
            FROM bookings
            $where
            ORDER BY id DESC
        ");

        if (!$query) {

            echo json_encode([
                "status" => "error",
                "message" => mysqli_error($conn)
            ]);

            exit;
        }

        $data = [];

        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }

        echo json_encode([
            "status" => "success",
            "data" => $data
        ]);

    break;

    

    case 'update_status':

        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? '';

        if ($id <= 0) {

            echo json_encode([
                "status" => "error",
                "message" => "ID tidak valid"
            ]);

            exit;
        }

        if (!in_array($status, ['disetujui', 'ditolak'])) {

            echo json_encode([
                "status" => "error",
                "message" => "Status tidak valid"
            ]);

            exit;
        }

        $id = (int)$id;

        $update = mysqli_query($conn, "
            UPDATE bookings
            SET status='$status'
            WHERE id='$id'
        ");

        if ($update) {

            echo json_encode([
                "status" => "success"
            ]);

        } else {

            echo json_encode([
                "status" => "error",
                "message" => mysqli_error($conn)
            ]);
        }

    break;

    
    case 'get_rooms':

        $query = mysqli_query($conn, "
            SELECT *
            FROM ruangan
            ORDER BY id ASC
        ");

        $data = [];

        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }

        echo json_encode([
            "status" => "success",
            "data" => $data
        ]);

    break;

    

    default:

        echo json_encode([
            "status" => "error",
            "message" => "Action tidak ditemukan"
        ]);

    break;
}
?>